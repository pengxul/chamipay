<?php

declare(strict_types=1);

namespace Pengxul\Pay\Shortcut\Wechat;


use Pengxul\Chamipay\Contract\ShortcutInterface;
use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidParamsException;
use Pengxul\Chamipay\Plugin\Wechat\Fund\MchTransfer\CancelPlugin;
use Pengxul\Supports\Str;

class CancelShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $method = Str::camel($params['_action'] ?? 'default') . 'Plugins';

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        throw new InvalidParamsException(Exception::PARAMS_SHORTCUT_ACTION_INVALID, "您所提供的 action 方法 [{$method}] 不支持，请参考文档或源码确认");
    }

    protected function defaultPlugins(): array
    {
        return $this->mchTransferPlugins();
    }

    protected function mchTransferPlugins(): array
    {
        return [
            CancelPlugin::class,
        ];
    }
}
