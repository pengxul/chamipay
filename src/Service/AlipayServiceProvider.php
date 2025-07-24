<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Service;

use Pengxul\Chamipay\Contract\ServiceProviderInterface;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Pay;
use Pengxul\Chamipay\Provider\Alipay;

class AlipayServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $service = new Alipay();

        Pay::set(Alipay::class, $service);
        Pay::set('alipay', $service);
    }
}
