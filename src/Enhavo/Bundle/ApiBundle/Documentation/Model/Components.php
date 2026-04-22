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

class Components extends Node
{
    public function schema($name): Schema
    {
        if (!array_key_exists('schemas', $this->data)) {
            $this->data['schemas'] = [];
        }

        if (!array_key_exists($name, $this->data['schemas'])) {
            $this->data['schemas'][$name] = [];
        }

        return new Schema($this->data['schemas'][$name], $this);
    }

    public function hasSchema(string $name): bool
    {
        if (!array_key_exists('schemas', $this->data)) {
            return false;
        }

        if (array_key_exists($name, $this->data['schemas'])) {
            return true;
        }

        return false;
    }

    public function response($name): Response
    {
        if (!array_key_exists('responses', $this->data)) {
            $this->data['responses'] = [];
        }

        if (!array_key_exists($name, $this->data['responses'])) {
            $this->data['responses'][$name] = [];
        }

        return new Response($this->data['responses'][$name], $this);
    }

    public function parameter($name): Parameter
    {
        if (!array_key_exists('parameters', $this->data)) {
            $this->data['parameters'] = [];
        }

        if (!array_key_exists($name, $this->data['parameters'])) {
            $this->data['parameters'][$name] = [];
        }

        return new Parameter($this->data['parameters'][$name], $this);
    }

    public function requestBody($name): RequestBody
    {
        if (!array_key_exists('requestBodies', $this->data)) {
            $this->data['requestBodies'] = [];
        }

        if (!array_key_exists($name, $this->data['requestBodies'])) {
            $this->data['requestBodies'][$name] = [];
        }

        return new RequestBody($this->data['requestBodies'][$name], $this);
    }

    public function securityScheme($name): SecurityScheme
    {
        if (!array_key_exists('securitySchemes', $this->data)) {
            $this->data['securitySchemes'] = [];
        }

        if (!array_key_exists($name, $this->data['securitySchemes'])) {
            $this->data['securitySchemes'][$name] = [];
        }

        return new SecurityScheme($this->data['securitySchemes'][$name], $this);
    }

    public function header($name): Header
    {
        if (!array_key_exists('headers', $this->data)) {
            $this->data['headers'] = [];
        }

        if (!array_key_exists($name, $this->data['headers'])) {
            $this->data['headers'][$name] = [];
        }

        return new Header($this->data['headers'][$name], $this);
    }
}
