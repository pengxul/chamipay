<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Unipay\OnlineGateway;

use Pengxul\Chamipay\Plugin\Unipay\GeneralPlugin;
use Pengxul\Chamipay\Rocket;

/**
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=755&apiservId=448&version=V2.2&bussType=0
 */
class CancelPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'gateway/api/backTransReq.do';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([
            'bizType' => '000000',
            'txnType' => '31',
            'txnSubType' => '00',
            'channelType' => '07',
        ]);
    }
}
