<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Provider;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Pengxul\Chamipay\Event;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidParamsException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Pay;
use Pengxul\Chamipay\Plugin\ParserPlugin;
use Pengxul\Chamipay\Plugin\Unipay\CallbackPlugin;
use Pengxul\Chamipay\Plugin\Unipay\LaunchPlugin;
use Pengxul\Chamipay\Plugin\Unipay\PreparePlugin;
use Pengxul\Chamipay\Plugin\Unipay\RadarSignPlugin;
use Pengxul\Supports\Collection;
use Pengxul\Supports\Str;

/**
 * @method ResponseInterface web(array $order) 电脑支付
 */
class Unipay extends AbstractProvider
{
    public const URL = [
        Pay::MODE_NORMAL => 'https://gateway.95516.com/',
        Pay::MODE_SANDBOX => 'https://gateway.test.95516.com/',
        Pay::MODE_SERVICE => 'https://gateway.95516.com',
    ];

    /**
     * @return null|array|Collection|MessageInterface
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function __call(string $shortcut, array $params)
    {
        $plugin = '\\Pengxul\\Pay\\Plugin\\Unipay\\Shortcut\\'.
            Str::studly($shortcut).'Shortcut';

        return $this->call($plugin, ...$params);
    }

    /**
     * @param array|string $order
     *
     * @return array|Collection
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function find($order)
    {
        if (!is_array($order)) {
            throw new InvalidParamsException(Exception::UNIPAY_FIND_STRING_NOT_SUPPORTED);
        }

        Event::dispatch(new Event\MethodCalled('unipay', __METHOD__, $order, null));

        return $this->__call('query', [$order]);
    }

    /**
     * @param array|string $order
     *
     * @return array|Collection
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function cancel($order)
    {
        if (!is_array($order)) {
            throw new InvalidParamsException(Exception::UNIPAY_CANCEL_STRING_NOT_SUPPORTED);
        }

        Event::dispatch(new Event\MethodCalled('unipay', __METHOD__, $order, null));

        return $this->__call('cancel', [$order]);
    }

    /**
     * @param array|string $order
     *
     * @return array|Collection
     *
     * @throws InvalidParamsException
     */
    public function close($order)
    {
        throw new InvalidParamsException(Exception::METHOD_NOT_SUPPORTED, 'Unipay does not support close api');
    }

    /**
     * @return array|Collection
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function refund(array $order)
    {
        Event::dispatch(new Event\MethodCalled('unipay', __METHOD__, $order, null));

        return $this->__call('refund', [$order]);
    }

    /**
     * @param null|array|ServerRequestInterface $contents
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     */
    public function callback($contents = null, ?array $params = null): Collection
    {
        $request = $this->getCallbackParams($contents);

        Event::dispatch(new Event\CallbackReceived('unipay', $request->all(), $params, null));

        return $this->pay(
            [CallbackPlugin::class],
            $request->merge($params)->all()
        );
    }

    public function success(): ResponseInterface
    {
        return new Response(200, [], 'success');
    }

    public function mergeCommonPlugins(array $plugins): array
    {
        return array_merge(
            [PreparePlugin::class],
            $plugins,
            [RadarSignPlugin::class],
            [LaunchPlugin::class, ParserPlugin::class],
        );
    }

    /**
     * @param null|array|ServerRequestInterface $contents
     */
    protected function getCallbackParams($contents = null): Collection
    {
        if (is_array($contents)) {
            return Collection::wrap($contents);
        }

        if ($contents instanceof ServerRequestInterface) {
            return Collection::wrap($contents->getParsedBody());
        }

        $request = ServerRequest::fromGlobals();

        return Collection::wrap($request->getParsedBody());
    }
}
