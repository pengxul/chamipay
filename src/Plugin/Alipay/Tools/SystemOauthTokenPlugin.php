<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Alipay\Tools;

use Pengxul\Chamipay\Plugin\Alipay\GeneralPlugin;
use Pengxul\Chamipay\Rocket;

/**
 * @see https://opendocs.alipay.com/open/02ailc
 */
class SystemOauthTokenPlugin extends GeneralPlugin
{
    protected function doSomethingBefore(Rocket $rocket): void
    {
        $rocket->mergePayload($rocket->getParams());
    }

    protected function getMethod(): string
    {
        return 'alipay.system.oauth.token';
    }
}
