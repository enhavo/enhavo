<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

class Context
{
    private int $statusCode = 200;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
