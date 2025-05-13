<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Context
{
    private int $statusCode = 200;
    private ?Response $response = null;
    private bool $stop = false;
    private array $headers = [];
    private array $data = [];

    public function __construct(
        private readonly Request $request,
    ) {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(Response $response, $stop = true): void
    {
        $this->response = $response;
        if ($stop) {
            $this->stop = true;
        }
    }

    public function setHeader($name, $value): self
    {
        $this->headers[strtolower($name)] = new Header($name, $value);

        return $this;
    }

    /** @return Header[] */
    public function getHeaders(): array
    {
        return array_values($this->headers);
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function removeHeader($name): self
    {
        if ($this->hasHeader($name)) {
            unset($this->headers[strtolower($name)]);
        }

        return $this;
    }

    public function get(string $key, $default = null)
    {
        return \array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    public function set(string $key, mixed $value)
    {
        $this->data[$key] = $value;
    }

    public function has(string $key)
    {
        return \array_key_exists($key, $this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function isStopped()
    {
        return $this->stop;
    }

    public function stop()
    {
        return $this->stop = true;
    }
}
