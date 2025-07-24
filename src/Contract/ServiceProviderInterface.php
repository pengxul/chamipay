<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Contract;

use Pengxul\Chamipay\Exception\ContainerException;

interface ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void;
}
