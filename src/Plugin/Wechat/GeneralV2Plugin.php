<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat;

use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Packer\XmlPacker;
use Pengxul\Chamipay\Rocket;

use function Pengxul\Chamipay\get_wechat_config;

abstract class GeneralV2Plugin extends GeneralPlugin
{
    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/xml',
            'User-Agent' => 'yansongda/pay-v3',
        ];
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());
        $configKey = $this->getConfigKey($rocket->getParams());

        $rocket->setPacker(XmlPacker::class)->mergeParams(['_version' => 'v2']);

        $rocket->mergePayload([
            'appid' => $config[$configKey] ?? '',
            'mch_id' => $config['mch_id'] ?? '',
        ]);
    }

    abstract protected function getUri(Rocket $rocket): string;
}
