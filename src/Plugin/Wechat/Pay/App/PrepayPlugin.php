<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Pay\App;

use Pengxul\Chamipay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_2_1.shtml
 */
class PrepayPlugin extends \Pengxul\Chamipay\Plugin\Wechat\Pay\Common\PrepayPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/pay/transactions/app';
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'v3/pay/partner/transactions/app';
    }

    protected function getConfigKey(array $params): string
    {
        return 'app_id';
    }
}
