<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Contract;

use Closure;
use Pengxul\Chamipay\Rocket;

interface PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket;
}
