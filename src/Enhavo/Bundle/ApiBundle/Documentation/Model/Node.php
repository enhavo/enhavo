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

class Node
{
    public function __construct(
        protected array &$data,
        protected $parent,
    ) {
    }

    public function set($data): void
    {
        foreach ($this->data as $key => $value) {
            unset($this->data[$key]);
        }

        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function get(): array
    {
        return $this->data;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function end()
    {
        return $this->parent;
    }

    public function getDocumentation(): Documentation
    {
        return $this->parent->getDocumentation();
    }
}
