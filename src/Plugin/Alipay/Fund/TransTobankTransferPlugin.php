<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Alipay\Fund;

use Pengxul\Chamipay\Plugin\Alipay\GeneralPlugin;

class TransTobankTransferPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.fund.trans.tobank.transfer';
    }
}
