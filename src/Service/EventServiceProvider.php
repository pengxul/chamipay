<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Service;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Pengxul\Chamipay\Contract\EventDispatcherInterface;
use Pengxul\Chamipay\Contract\ServiceProviderInterface;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Pay;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        if (class_exists(EventDispatcher::class)) {
            Pay::set(EventDispatcherInterface::class, new EventDispatcher());
        }
    }
}
