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

    public function get($key, $default = null)
    {
        return \array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    public function set($key, $value)
    {
        if (!is_array($value) && !is_scalar($value)) {
            throw new \Exception(sprintf('Data value must be of type array or scalar. "%s" given.', gettype($value)));
        }

        $this->data[$key] = $value;
    }

    public function has($key)
    {
        return \array_key_exists($key, $this->data);
    }
}
