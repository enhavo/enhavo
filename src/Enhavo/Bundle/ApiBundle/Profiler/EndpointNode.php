<?php

namespace Enhavo\Bundle\ApiBundle\Profiler;

class EndpointNode
{
    /** @var string[]  */
    private array $extensions = [];

    public function __construct(
        private string $type
    )
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function addExtension(string $extension)
    {
        $this->extensions[] = $extension;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
