<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormView;

final class VueData implements \IteratorAggregate, \Countable, \ArrayAccess
{
    private ?VueData $parent = null;
    private array $normalizer = [];
    private array $children = [];

    public function __construct(
        private FormView $formView
    )
    {
    }

    public function keys()
    {
        return array_keys($this->data);
    }

    public function replace(array $parameters = [])
    {
        $this->data = $parameters;
    }

    public function add(array $parameters = [])
    {
        $this->data = array_replace($this->data, $parameters);
    }

    public function get($key, $default = null)
    {
        return \array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function has($key)
    {
        return \array_key_exists($key, $this->data);
    }

    public function remove($key)
    {
        unset($this->data[$key]);
    }

    public function getIterator():\Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function count(): int
    {
        return \count($this->data);
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function toArray()
    {
        $array = [];
        foreach($this->data as $key => $value) {
            $array[$key] = $value;
        }

        $array['children'] = [];
        foreach ($this->getChildren() as $key => $child) {
            $childArray = $child->toArray();
            $childArray['name'] = (string)$key;
            $array['children'][] = $childArray;
        }
        return $array;
    }

    public function getFormView(): ?FormView
    {
        return $this->formView;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild($key, VueData $data)
    {
        $data->setParent($this);
        $this->children[$key] = $data;
    }

    public function removeChild($key)
    {
        unset($this->children[$key]);
    }

    public function getParent(): ?VueData
    {
        return $this->parent;
    }

    public function setParent(?VueData $parent): void
    {
        $this->parent = $parent;
    }

    public function addNormalizer(callable $callback)
    {
        $this->normalizer[] = $callback;
    }

    /** @return callable[] */
    public function getNormalizer(): array
    {
        return $this->normalizer;
    }
}
