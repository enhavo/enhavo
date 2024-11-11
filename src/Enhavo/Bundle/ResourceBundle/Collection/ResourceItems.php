<?php

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Enhavo\Bundle\ApiBundle\Data\Data;

class ResourceItems implements \Iterator
{
    private int $position = 0;

    public function __construct(
        private readonly array $items,
        private readonly Data $meta = new Data(),
    )
    {
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getMeta(): Data
    {
        return $this->meta;
    }

    public function normalize(): array
    {
        $items = [];
        foreach ($this->getItems() as $item) {
            $items[] = $item->normalize();
        }

        return [
            'items' => $items,
            'meta' => $this->meta->normalize()
        ];
    }

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }
}
