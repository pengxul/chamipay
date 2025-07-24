<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Unipay\Shortcut;

use Pengxul\Chamipay\Contract\ShortcutInterface;
use Pengxul\Chamipay\Plugin\Unipay\HtmlResponsePlugin;
use Pengxul\Chamipay\Plugin\Unipay\OnlineGateway\PagePayPlugin;

class WebShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
