<?php

declare(strict_types=1);

namespace Pengxul\Chamipay;

use JsonSerializable as JsonSerializableInterface;
use Pengxul\Supports\Traits\Accessable;
use Pengxul\Supports\Traits\Arrayable;
use Pengxul\Supports\Traits\Serializable;

class Request extends \GuzzleHttp\Psr7\Request implements JsonSerializableInterface
{
    use Accessable;
    use Arrayable;
    use Serializable;

    public function toArray(): array
    {
        return [
            'url' => $this->getUri()->__toString(),
            'method' => $this->getMethod(),
            'headers' => $this->getHeaders(),
            'body' => (string) $this->getBody(),
        ];
    }
}
