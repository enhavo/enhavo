<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

class VueRoute
{
    /** @var VueRoute[] */
    private array $children = [];
    private ?string $name;
    private ?string $path;
    private string|array|null $meta;
    private ?string $component;

    public function __construct(?array $data = null)
    {
        $this->name = $data['name'] ?? null;
        $this->path = $data['path'] ?? null;
        $this->meta = $data['meta'] ?? null;
        $this->component = $data['component'] ?? null;

        if (isset($data['children'])) {
            foreach ($data['children'] as $child) {
                if (!is_array($child)) {
                    throw new \Exception(sprintf('Can\'t create child component for "%s" with path "%s". Must be array, but got "%s"', $this->name, $this->path, gettype($child)));
                }
                $this->children[] = new self($child);
            }
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getMeta(): string|array|null
    {
        return $this->meta;
    }

    public function setMeta(string|array|null $meta): void
    {
        $this->meta = $meta;
    }

    public function addChildren(VueRoute $child)
    {
        $this->children[] = $child;
    }

    public function removeChildren(VueRoute $child)
    {
        if (false !== $key = array_search($child, $this->children, true)) {
            array_splice($this->children, $key, 1);
        }
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getComponent(): ?string
    {
        return $this->component;
    }

    public function setComponent(?string $component): void
    {
        $this->component = $component;
    }
}
