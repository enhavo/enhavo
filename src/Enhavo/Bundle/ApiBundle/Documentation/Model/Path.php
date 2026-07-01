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

class Path extends Node
{
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

    public function method(string $verb): Method
    {
        if (!array_key_exists($verb, $this->data)) {
            $this->data[$verb] = [];
        }

        return new Method($this->data[$verb], $this);
    }
}
