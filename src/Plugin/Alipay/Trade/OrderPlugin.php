<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Alipay\Trade;

use Pengxul\Chamipay\Plugin\Alipay\GeneralPlugin;

class OrderPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.order.pay';
    }
}
