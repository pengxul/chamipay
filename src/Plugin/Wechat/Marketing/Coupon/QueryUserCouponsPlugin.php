<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Marketing\Coupon;

use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidParamsException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Chamipay\Rocket;

use function Pengxul\Chamipay\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter9_1_9.shtml
 */
class QueryUserCouponsPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }

    /**
     * @throws InvalidParamsException
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();
        $params = $rocket->getParams();
        $config = get_wechat_config($params);

        if (!$payload->has('openid')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        if (!$payload->has('appid')) {
            $rocket->mergePayload(['appid' => $config[$this->getConfigKey($params)] ?? '']);
        }

        if (!$payload->has('creator_mchid')) {
            $rocket->mergePayload(['creator_mchid' => $config['mch_id']]);
        }

        $query = $rocket->getPayload()->all();

        unset($query['openid']);

        return 'v3/marketing/favor/users/'.
            $payload->get('openid').
            '/coupons?'.http_build_query($query);
    }
}
