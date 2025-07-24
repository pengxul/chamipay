<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Service;

use Pengxul\Chamipay\Contract\ConfigInterface;
use Pengxul\Chamipay\Contract\ServiceProviderInterface;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Pay;
use Pengxul\Supports\Config;

class ConfigServiceProvider implements ServiceProviderInterface
{
    private array $config = [
        'logger' => [
            'enable' => false,
            'file' => null,
            'identify' => 'yansongda.pay',
            'level' => 'debug',
            'type' => 'daily',
            'max_files' => 30,
        ],
        'http' => [
            'timeout' => 5.0,
            'connect_timeout' => 3.0,
            'headers' => [
                'User-Agent' => 'yansongda/pay-v3',
            ],
        ],
        'mode' => Pay::MODE_NORMAL,
    ];

    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $config = new Config(array_replace_recursive($this->config, $data ?? []));

        Pay::set(ConfigInterface::class, $config);
    }
}
