<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Plugin;

use Closure;
use Psr\Http\Message\ResponseInterface;
use Pengxul\Chamipay\Contract\DirectionInterface;
use Pengxul\Chamipay\Contract\PackerInterface;
use Pengxul\Chamipay\Contract\PluginInterface;
use Pengxul\Chamipay\Exception\ContainerException;
use Pengxul\Chamipay\Exception\Exception;
use Pengxul\Chamipay\Exception\InvalidConfigException;
use Pengxul\Chamipay\Exception\ServiceNotFoundException;
use Pengxul\Chamipay\Pay;
use Pengxul\Chamipay\Rocket;

class ParserPlugin implements PluginInterface
{
    /**
     * @throws ServiceNotFoundException
     * @throws ContainerException
     * @throws InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        /* @var ResponseInterface $response */
        $response = $rocket->getDestination();

        return $rocket->setDestination(
            $this->getDirection($rocket)->parse($this->getPacker($rocket), $response)
        );
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    protected function getDirection(Rocket $rocket): DirectionInterface
    {
        $packer = Pay::get($rocket->getDirection());

        $packer = is_string($packer) ? Pay::get($packer) : $packer;

        if (!$packer instanceof DirectionInterface) {
            throw new InvalidConfigException(Exception::INVALID_PARSER);
        }

        return $packer;
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    protected function getPacker(Rocket $rocket): PackerInterface
    {
        $packer = Pay::get($rocket->getPacker());

        $packer = is_string($packer) ? Pay::get($packer) : $packer;

        if (!$packer instanceof PackerInterface) {
            throw new InvalidConfigException(Exception::INVALID_PACKER);
        }

        return $packer;
    }
}
