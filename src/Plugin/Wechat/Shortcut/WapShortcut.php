<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Shortcut;

use Pengxul\Chamipay\Contract\ShortcutInterface;
use Pengxul\Chamipay\Plugin\Wechat\Pay\H5\PrepayPlugin;

class WapShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PrepayPlugin::class,
        ];
    }
}
