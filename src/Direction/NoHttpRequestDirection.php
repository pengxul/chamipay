<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Chamipay\Contract\DirectionInterface;
use Pengxul\Chamipay\Contract\PackerInterface;

class NoHttpRequestDirection implements DirectionInterface
{
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        return $response;
    }
}
