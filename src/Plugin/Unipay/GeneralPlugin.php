<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Unipay;

use Closure;
use Psr\Http\Message\RequestInterface;
use Pengxul\Chamipay\Contract\PluginInterface;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Logger;
use Pengxul\Chamipay\Pay;
use Pengxul\Chamipay\Provider\Unipay;
use Pengxul\Chamipay\Request;
use Pengxul\Chamipay\Rocket;

use function Pengxul\Chamipay\get_unipay_config;

abstract class GeneralPlugin implements PluginInterface
{
    /**
     * @throws ServiceNotFoundException
     * @throws ContainerException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[unipay][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->getRequest($rocket));
        $this->doSomething($rocket);

        Logger::info('[unipay][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getRequest(Rocket $rocket): RequestInterface
    {
        return new Request(
            $this->getMethod(),
            $this->getUrl($rocket),
            $this->getHeaders(),
        );
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getUrl(Rocket $rocket): string
    {
        $url = $this->getUri($rocket);

        if (0 === strpos($url, 'http')) {
            return $url;
        }

        $config = get_unipay_config($rocket->getParams());

        return Unipay::URL[$config['mode'] ?? Pay::MODE_NORMAL].$url;
    }

    protected function getHeaders(): array
    {
        return [
            'User-Agent' => 'yansongda/pay-v3',
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];
    }

    abstract protected function doSomething(Rocket $rocket): void;

    abstract protected function getUri(Rocket $rocket): string;
}
