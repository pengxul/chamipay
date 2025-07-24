<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Alipay\Shortcut;

use Pengxul\Chamipay\Contract\ShortcutInterface;
use Pengxul\Chamipay\Plugin\Alipay\HtmlResponsePlugin;
use Pengxul\Chamipay\Plugin\Alipay\Trade\PagePayPlugin;

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
