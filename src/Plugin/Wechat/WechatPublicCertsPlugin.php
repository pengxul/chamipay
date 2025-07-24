<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat;

use Pengxul\Chamipay\Rocket;

class WechatPublicCertsPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/certificates';
    }
}
