<?php

declare(strict_types=1);

namespace Pengxul\Chamipay\Event;

use Pengxul\Chamipay\Rocket;

class Event
{
    public ?Rocket $rocket = null;

    public function __construct(?Rocket $rocket = null)
    {
        $this->rocket = $rocket;
    }
}
