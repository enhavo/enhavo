<?php

namespace Enhavo\Bundle\ApiBundle\Documentation\Model;

class Node
{
    public function __construct(
        protected array &$data,
        protected Node $parent,
    ) {
    }

    public function set($data): void
    {
        foreach ($this->data as $key => $value) {
            unset($this->data[$key]);
        }

        foreach ($data as $key => $value) {
            $data[$key] = $value;
        }
    }

    public function get(): array
    {
        return $this->data;
    }

    public function getParent(): Node
    {
        return $this->parent;
    }

    public function end(): Node
    {
        return $this->parent;
    }

    public function getDocumentation(): Documentation
    {
        $node = $this;
        while ($node !== null) {
            if ($node instanceof Documentation) {
                return $node;
            }
            $node = $node->getParent();
        }

        throw new \Exception('No Documentation parent found');
    }
}
