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

class Path
{
    public function __construct(
        private array &$path,
        private Documentation $parent,
    ) {
    }

    public function description($description)
    {
        $this->path['description'] = $description;
    }

    public function summary($summary): self
    {
        $this->path['summary'] = $summary;

        return $this;
    }

    public function parameter($name): Parameter
    {
        if (!array_key_exists('parameters', $this->path)) {
            $this->path['parameters'] = [];
        }

        foreach ($this->path['parameters'] as $key => $parameter) {
            if ($parameter['name'] === $name) {
                return new Parameter($this->path['parameters'][$key], $this);
            }
        }

        $parameter = [
            'name' => $name,
        ];

        $this->path['parameters'][] = &$parameter;

        return new Parameter($parameter, $this);
    }

    public function method(string $verb): Method
    {
        if (!array_key_exists($verb, $this->path)) {
            $this->path[$verb] = [];
        }

        return new Method($this->path[$verb], $this);
    }

    public function end(): Documentation
    {
        return $this->parent;
    }

    public function getDocumentation(): Documentation
    {
        return $this->parent->getDocumentation();
    }
}
