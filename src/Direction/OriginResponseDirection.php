<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Chamipay\Contract\DirectionInterface;
use Pengxul\Chamipay\Contract\PackerInterface;
use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidResponseException;

class OriginResponseDirection implements DirectionInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        if (!is_null($response)) {
            return $response;
        }

        throw new InvalidResponseException(Exception::INVALID_RESPONSE_CODE);
    }
}
