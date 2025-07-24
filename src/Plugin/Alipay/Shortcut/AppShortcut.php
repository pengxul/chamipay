<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin\Alipay\Shortcut;

use Closure;
use GuzzleHttp\Psr7\Response;
use Pengxul\Chamipay\Contract\PluginInterface;
use Pengxul\Chamipay\Contract\ShortcutInterface;
use Pengxul\Chamipay\Plugin\Alipay\Trade\AppPayPlugin;
use Pengxul\Chamipay\Rocket;
use Pengxul\Supports\Arr;
use Pengxul\Supports\Collection;

class AppShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            AppPayPlugin::class,
            $this->buildResponse(),
        ];
    }

    protected function buildResponse(): PluginInterface
    {
        return new class() implements PluginInterface {
            public function assembly(Rocket $rocket, Closure $next): Rocket
            {
                $rocket->setDestination(new Response());

                /* @var Rocket $rocket */
                $rocket = $next($rocket);

                $response = $this->buildHtml($rocket->getPayload());

                return $rocket->setDestination($response);
            }

            protected function buildHtml(Collection $payload): Response
            {
                return new Response(200, [], Arr::query($payload->all()));
            }
        };
    }
}
