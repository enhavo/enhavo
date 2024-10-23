<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Mock;

class ResourceMock
{
    public ?int $id = null;
    public ?string $name = null;
    public mixed $data = null;
    public array $children = [];

    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
