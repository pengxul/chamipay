<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Chamipay\Contract\DirectionInterface;
use Pengxul\Chamipay\Contract\PackerInterface;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Pay;
use Pengxul\Supports\Collection;

class CollectionDirection implements DirectionInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): Collection
    {
        return new Collection(
            Pay::get(ArrayDirection::class)->parse($packer, $response)
        );
    }
}
