<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Fund\Transfer;

use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidParamsException;
use Pengxul\Chamipay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Chamipay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_2.shtml
 */
class QueryBatchIdPlugin extends GeneralPlugin
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
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('batch_id') || !$payload->has('need_query_detail')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $batchId = $payload->get('batch_id');

        $payload->forget('batch_id');

        return 'v3/transfer/batches/batch-id/'.$batchId.
            '?'.$payload->query();
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('batch_id') || !$payload->has('need_query_detail')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $batchId = $payload->get('batch_id');

        $payload->forget('batch_id');

        return 'v3/partner-transfer/batches/batch-id/'.$batchId.
            '?'.$payload->query();
    }
}
