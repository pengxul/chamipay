<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Shortcut;

use Pengxul\Chamipay\Contract\ShortcutInterface;
use Pengxul\Chamipay\Plugin\Wechat\Pay\App\InvokePrepayPlugin;
use Pengxul\Chamipay\Plugin\Wechat\Pay\App\PrepayPlugin;

class AppShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PrepayPlugin::class,
            InvokePrepayPlugin::class,
        ];
    }
}
