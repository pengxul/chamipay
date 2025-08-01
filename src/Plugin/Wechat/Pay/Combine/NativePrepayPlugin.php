<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Pay\Combine;

use Pengxul\Chamipay\Plugin\Wechat\Pay\Common\CombinePrepayPlugin;
use Pengxul\Chamipay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter5_1_5.shtml
 */
class NativePrepayPlugin extends CombinePrepayPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/combine-transactions/native';
    }
}
