<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Alipay\Data;

use Pengxul\Chamipay\Plugin\Alipay\GeneralPlugin;

/**
 * @see https://opendocs.alipay.com/open/02fkbl
 */
class BillDownloadUrlQueryPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.data.dataservice.bill.downloadurl.query';
    }
}
