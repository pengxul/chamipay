<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Shortcut;

use Pengxul\Chamipay\Contract\ShortcutInterface;
use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidParamsException;
use Pengxul\Chamipay\Plugin\Wechat\Fund\Transfer\CreatePlugin;
use Pengxul\Chamipay\Plugin\Wechat\Fund\Transfer\QueryMchTransferPlugin;
use Pengxul\Supports\Str;

class TransferShortcut implements ShortcutInterface
{

    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        if (isset($params['combine_out_trade_no'])) {
            return $this->combinePlugins();
        }

        $typeMethod = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Transfer action [{$typeMethod}] not supported");

    }


    function defaultPlugins(): array
    {
        return [
            CreatePlugin::class,
        ];
    }

    protected function mchTransferPlugins(): array
    {
        return [
            \Pengxul\Chamipay\Plugin\Wechat\Fund\MchTransfer\CreatePlugin::class,
        ];
    }
}
