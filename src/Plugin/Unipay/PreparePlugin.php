<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Unipay;

use Closure;
use Pengxul\Chamipay\Contract\PluginInterface;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\InvalidConfigException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Logger;
use Pengxul\Chamipay\Rocket;
use Pengxul\Chamipay\Traits\GetUnipayCerts;
use Pengxul\Supports\Str;

use function Pengxul\Chamipay\get_tenant;
use function Pengxul\Chamipay\get_unipay_config;

class PreparePlugin implements PluginInterface
{
    use GetUnipayCerts;

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[unipay][PreparePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload($this->getPayload($rocket->getParams()));

        Logger::info('[unipay][PreparePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidConfigException
     */
    protected function getPayload(array $params): array
    {
        $tenant = get_tenant($params);
        $config = get_unipay_config($params);

        $init = [
            'version' => '5.1.0',
            'encoding' => 'utf-8',
            'backUrl' => $config['notify_url'] ?? '',
            'currencyCode' => '156',
            'accessType' => '0',
            'signature' => '',
            'signMethod' => '01',
            'merId' => $config['mch_id'] ?? '',
            'frontUrl' => $config['return_url'] ?? '',
            'certId' => $this->getCertId($tenant, $config),
        ];

        return array_merge(
            $init,
            array_filter($params, fn ($v, $k) => !Str::startsWith(strval($k), '_'), ARRAY_FILTER_USE_BOTH),
        );
    }
}
