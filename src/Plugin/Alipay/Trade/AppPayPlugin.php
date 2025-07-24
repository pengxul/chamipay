<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Alipay\Trade;

use Closure;
use Pengxul\Chamipay\Contract\PluginInterface;
use Pengxul\Chamipay\Direction\ResponseDirection;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Logger;
use Pengxul\Chamipay\Rocket;
use Pengxul\Chamipay\Traits\SupportServiceProviderTrait;

/**
 * @see https://opendocs.alipay.com/open/02e7gq?scene=common
 */
class AppPayPlugin implements PluginInterface
{
    use SupportServiceProviderTrait;

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][AppPayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->loadAlipayServiceProvider($rocket);

        $rocket->setDirection(ResponseDirection::class)
            ->mergePayload([
                'method' => 'alipay.trade.app.pay',
                'biz_content' => array_merge(
                    ['product_code' => 'QUICK_MSECURITY_PAY'],
                    $rocket->getParams(),
                ),
            ])
        ;

        Logger::info('[alipay][AppPayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
