<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Provider;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\MessageInterface;
use Throwable;
use Pengxul\Chamipay\Contract\HttpClientInterface;
use Pengxul\Chamipay\Contract\PluginInterface;
use Pengxul\Chamipay\Contract\ProviderInterface;
use Pengxul\Chamipay\Contract\ShortcutInterface;
use Pengxul\Chamipay\Direction\ArrayDirection;
use Pengxul\Chamipay\Event;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidConfigException;
use Pengxul\Chamipay\Exception\InvalidParamsException;
use Pengxul\Chamipay\Exception\InvalidResponseException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Logger;
use Pengxul\Chamipay\Pay;
use Pengxul\Chamipay\Rocket;
use Pengxul\Supports\Collection;
use Pengxul\Supports\Pipeline;

use function Pengxul\Chamipay\should_do_http_request;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @return null|array|Collection|MessageInterface
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function call(string $plugin, array $params = [])
    {
        if (!class_exists($plugin) || !in_array(ShortcutInterface::class, class_implements($plugin))) {
            throw new InvalidParamsException(Exception::SHORTCUT_NOT_FOUND, "[{$plugin}] is not incompatible");
        }

        /* @var ShortcutInterface $money */
        $money = Pay::get($plugin);

        $plugins = $money->getPlugins($params);

        if (empty($params['_no_common_plugins'])) {
            $plugins = $this->mergeCommonPlugins($plugins);
        }

        return $this->pay($plugins, $params);
    }

    /**
     * @return null|array|Collection|MessageInterface
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     */
    public function pay(array $plugins, array $params)
    {
        Logger::info('[AbstractProvider] 即将进行 pay 操作', func_get_args());

        Event::dispatch(new Event\PayStarted($plugins, $params, null));

        $this->verifyPlugin($plugins);

        /* @var Pipeline $pipeline */
        $pipeline = Pay::make(Pipeline::class);

        /* @var Rocket $rocket */
        $rocket = $pipeline
            ->send((new Rocket())->setParams($params)->setPayload(new Collection()))
            ->through($plugins)
            ->via('assembly')
            ->then(fn ($rocket) => $this->ignite($rocket))
        ;

        Event::dispatch(new Event\PayFinish($rocket));

        $destination = $rocket->getDestination();

        if (ArrayDirection::class === $rocket->getDirection() && $destination instanceof Collection) {
            return $destination->toArray();
        }

        return $destination;
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidResponseException
     * @throws InvalidConfigException
     */
    public function ignite(Rocket $rocket): Rocket
    {
        if (!should_do_http_request($rocket->getDirection())) {
            return $rocket;
        }

        /* @var HttpClientInterface $http */
        $http = Pay::get(HttpClientInterface::class);

        if (!$http instanceof ClientInterface) {
            throw new InvalidConfigException(Exception::HTTP_CLIENT_CONFIG_ERROR);
        }

        Logger::info('[AbstractProvider] 准备请求支付服务商 API', $rocket->toArray());

        Event::dispatch(new Event\ApiRequesting($rocket));

        try {
            $response = $http->sendRequest($rocket->getRadar());

            $contents = (string) $response->getBody();

            $rocket->setDestination($response->withBody(Utils::streamFor($contents)))
                ->setDestinationOrigin($response->withBody(Utils::streamFor($contents)))
            ;
        } catch (Throwable $e) {
            Logger::error('[AbstractProvider] 请求支付服务商 API 出错', ['message' => $e->getMessage(), 'rocket' => $rocket->toArray(), 'trace' => $e->getTrace()]);

            throw new InvalidResponseException(Exception::REQUEST_RESPONSE_ERROR, $e->getMessage(), [], $e);
        }

        Logger::info('[AbstractProvider] 请求支付服务商 API 成功', ['response' => $response, 'rocket' => $rocket->toArray()]);

        Event::dispatch(new Event\ApiRequested($rocket));

        return $rocket;
    }

    abstract public function mergeCommonPlugins(array $plugins): array;

    /**
     * @throws InvalidParamsException
     */
    protected function verifyPlugin(array $plugins): void
    {
        foreach ($plugins as $plugin) {
            if (is_callable($plugin)) {
                continue;
            }

            if ((is_object($plugin)
                    || (is_string($plugin) && class_exists($plugin)))
                && in_array(PluginInterface::class, class_implements($plugin))) {
                continue;
            }

            throw new InvalidParamsException(Exception::PLUGIN_ERROR, "[{$plugin}] is not incompatible");
        }
    }
}
