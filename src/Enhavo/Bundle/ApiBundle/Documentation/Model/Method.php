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



class Method extends Node
{
    public const GET = 'get';
    public const PUT = 'put';
    public const POST = 'post';
    public const DELETE = 'delete';
    public const OPTIONS = 'options';
    public const HEAD = 'head';
    public const PATCH = 'patch';
    public const TRACE = 'trace';


    public function ref($ref): self
    {
        $this->data['$ref'] = $ref;

        return $this;
    }

    public function description($description): self
    {
        $this->data['description'] = $description;

        return $this;
    }

    public function summary($summary): self
    {
        $this->data['summary'] = $summary;

        return $this;
    }

    public function operationId($operationId): self
    {
        $this->data['operationId'] = $operationId;

        return $this;
    }

    public function response($code): Response
    {
        if (!isset($this->data['responses'])) {
            $this->data['responses'] = [];
        }

        if (!array_key_exists($code, $this->data['responses'])) {
            $this->data['responses'][$code] = [];
        }

        return new Response($this->data['responses'][$code], $this);
    }

    public function parameter($name): Parameter
    {
        if (!array_key_exists('parameters', $this->data)) {
            $this->data['parameters'] = [];
        }

        foreach ($this->data['parameters'] as $key => $parameter) {
            if ($parameter['name'] === $name) {
                return new Parameter($this->data['parameters'][$key], $this);
            }
        }

        $parameter = [
            'name' => $name,
        ];

        $this->data['parameters'][] = &$parameter;

        return new Parameter($parameter, $this);
    }

    public function requestBody(): RequestBody
    {
        if (!array_key_exists('requestBody', $this->data)) {
            $this->data['requestBody'] = [];
        }

        return new RequestBody($this->data['requestBody'], $this);
    }

    public function tags(array $tags): self
    {
        $this->data['tags'] = $tags;

        return $this;
    }

    public function deprecated(bool $deprecated = true): self
    {
        $this->data['deprecated'] = $deprecated;

        return $this;
    }

    public function security(array $requirement): self
    {
        if (!array_key_exists('security', $this->data)) {
            $this->data['security'] = [];
        }

        $this->data['security'][] = $requirement;

        return $this;
    }
}
