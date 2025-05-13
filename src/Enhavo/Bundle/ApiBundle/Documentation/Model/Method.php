<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Documentation\Model;

class Method
{
    public const GET = 'get';
    public const PUT = 'put';
    public const POST = 'post';
    public const DELETE = 'delete';
    public const OPTIONS = 'options';
    public const HEAD = 'head';
    public const PATCH = 'patch';
    public const TRACE = 'trace';

    public function __construct(
        private array &$method,
        private Path $parent,
    ) {
    }

    public function ref($ref): self
    {
        $this->method['$ref'] = $ref;

        return $this;
    }

    public function description($description): self
    {
        $this->method['description'] = $description;

        return $this;
    }

    public function summary($summary): self
    {
        $this->method['summary'] = $summary;

        return $this;
    }

    public function operationId($operationId): self
    {
        $this->method['operationId'] = $operationId;

        return $this;
    }

    public function response($code): Response
    {
        if (!isset($this->method['responses'])) {
            $this->method['responses'] = [];
        }

        if (!array_key_exists($code, $this->method['responses'])) {
            $this->method['responses'][$code] = [];
        }

        return new Response($this->method['responses'][$code], $this);
    }

    public function parameter($name): Parameter
    {
        if (!array_key_exists('parameters', $this->method)) {
            $this->method['parameters'] = [];
        }

        foreach ($this->method['parameters'] as $key => $parameter) {
            if ($parameter['name'] === $name) {
                return new Parameter($this->method['parameters'][$key], $this);
            }
        }

        $parameter = [
            'name' => $name,
        ];

        $this->method['parameters'][] = &$parameter;

        return new Parameter($parameter, $this);
    }

    public function end(): Path
    {
        return $this->parent;
    }

    public function getDocumentation(): Documentation
    {
        return $this->parent->getDocumentation();
    }
}
