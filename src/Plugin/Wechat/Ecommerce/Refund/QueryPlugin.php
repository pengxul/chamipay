<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Ecommerce\Refund;

use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidParamsException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Chamipay\Rocket;

use function Pengxul\Chamipay\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter7_6_2.shtml
 */
class QueryPlugin extends GeneralPlugin
{
    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        throw new InvalidParamsException(Exception::NOT_IN_SERVICE_MODE);
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();
        $config = get_wechat_config($rocket->getParams());
        $subMchId = $payload->get('sub_mchid', $config['sub_mch_id'] ?? '');

        if ($payload->has('refund_id')) {
            return 'v3/ecommerce/refunds/id/'.$payload->get('refund_id').'?sub_mchid='.$subMchId;
        }

        if ($payload->has('out_refund_no')) {
            return 'v3/ecommerce/refunds/out-refund-no/'.$payload->get('out_refund_no').'?sub_mchid='.$subMchId;
        }

        throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }
}
