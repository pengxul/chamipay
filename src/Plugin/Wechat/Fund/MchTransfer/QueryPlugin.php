<?php

namespace Pengxul\Chamipay\Plugin\Wechat\Fund\MchTransfer;

use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidParamsException;
use Pengxul\Chamipay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Chamipay\Rocket;

class QueryPlugin extends GeneralPlugin
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

        if ($payload->has('out_bill_no')) {
            return 'v3/fund-app/mch-transfer/transfer-bills/out-bill-no'.
            $payload->get('out_batch_no');
        } else if ($payload->has('transfer_bill_no')) {
            return 'v3/fund-app/mch-transfer/transfer-bills/transfer-bill-no'.
                $payload->get('out_batch_no');
        }

        throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('out_batch_no') || !$payload->has('out_detail_no')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/partner-transfer/batches/out-batch-no/'.
            $payload->get('out_batch_no').
            '/details/out-detail-no/'.
            $payload->get('out_detail_no');
    }
}
