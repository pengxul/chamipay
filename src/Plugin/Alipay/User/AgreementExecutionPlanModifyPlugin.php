<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Alipay\User;

use Pengxul\Chamipay\Plugin\Alipay\GeneralPlugin;

/**
 * @see https://opendocs.alipay.com/open/02fkaq?ref=api
 */
class AgreementExecutionPlanModifyPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.user.agreement.executionplan.modify';
    }
}
