<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Pay\Mini;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_5_4.shtml
 */
class InvokePrepayPlugin extends \Pengxul\Chamipay\Plugin\Wechat\Pay\Common\InvokePrepayPlugin
{
    protected function getConfigKey(): string
    {
        return 'mini_app_id';
    }
}
