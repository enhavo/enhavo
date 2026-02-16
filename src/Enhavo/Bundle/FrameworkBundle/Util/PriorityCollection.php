<?php

namespace Enhavo\Bundle\FrameworkBundle\Util;

class PriorityCollection implements \Countable, \Iterator
{
    private array $items = [];
    private int $position = 0;
    private array $keys = [];

    public function add($data, $priority = 1): self
    {
        $priority      = (int) $priority;
        $this->items[] = [
            'data'     => $data,
            'priority' => $priority,
        ];

        usort($this->items, fn($a, $b) => $b['priority'] <=> $a['priority']);

        return $this;
    }

    public function removeElement(mixed $element): bool
    {
        $found = false;
        $key   = null;
        foreach ($this->items as $key => $item) {
            if ($item['data'] === $element) {
                $found = true;
                break;
            }
        }
        if ($found && $key !== null) {
            unset($this->items[$key]);
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this->items as $item) {
            $array[] = $item['data'];
        }
        return $array;
    }

    public function contains($element): bool
    {
        return array_any($this->items, fn($item) => $item['data'] === $element);
    }

    public function current(): mixed
    {
        $key = $this->keys[$this->position];
        return $this->items[$key]['data'];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->keys[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
        $this->keys = array_keys($this->items);
    }
}
