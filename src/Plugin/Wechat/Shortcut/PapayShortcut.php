<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Wechat\Shortcut;

use Pengxul\Chamipay\Contract\ShortcutInterface;
use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidParamsException;
use Pengxul\Chamipay\Plugin\ParserPlugin;
use Pengxul\Chamipay\Plugin\Wechat\Papay\ApplyPlugin;
use Pengxul\Chamipay\Plugin\Wechat\Papay\ContractOrderPlugin;
use Pengxul\Chamipay\Plugin\Wechat\Papay\OnlyContractPlugin;
use Pengxul\Chamipay\Plugin\Wechat\Pay\Common\InvokePrepayV2Plugin;
use Pengxul\Chamipay\Plugin\Wechat\PreparePlugin;
use Pengxul\Chamipay\Plugin\Wechat\RadarSignPlugin;
use Pengxul\Supports\Str;

class PapayShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}($params);
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Papay action [{$typeMethod}] not supported");
    }

    /**
     * 返回只签约（委托代扣）参数.
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_3.shtml
     */
    public function ContractPlugins(): array
    {
        return [
            PreparePlugin::class,
            OnlyContractPlugin::class,
        ];
    }

    /**
     * 申请代扣.
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_8.shtml
     */
    public function applyPlugins(): array
    {
        return [
            PreparePlugin::class,
            ApplyPlugin::class,
            RadarSignPlugin::class,
            ParserPlugin::class,
        ];
    }

    /**
     * 支付中签约.
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_5.shtml
     */
    protected function defaultPlugins(array $params): array
    {
        return [
            PreparePlugin::class,
            ContractOrderPlugin::class,
            RadarSignPlugin::class,
            $this->getInvoke($params),
            ParserPlugin::class,
        ];
    }

    protected function getInvoke(array $params): string
    {
        switch ($params['_type'] ?? 'default') {
            case 'app':
                return \Pengxul\Chamipay\Plugin\Wechat\Pay\App\InvokePrepayV2Plugin::class;

            case 'mini':
                return \Pengxul\Chamipay\Plugin\Wechat\Pay\Mini\InvokePrepayV2Plugin::class;

            default:
                return InvokePrepayV2Plugin::class;
        }
    }
}
