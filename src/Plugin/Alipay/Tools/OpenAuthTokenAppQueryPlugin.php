<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Alipay\Tools;

use Pengxul\Chamipay\Plugin\Alipay\GeneralPlugin;

/**
 * @see https://opendocs.alipay.com/isv/03l8ca
 */
class OpenAuthTokenAppQueryPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.open.auth.token.app.query';
    }
}
