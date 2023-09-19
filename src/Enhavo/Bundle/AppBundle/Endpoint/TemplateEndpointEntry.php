<?php

namespace Enhavo\Bundle\AppBundle\Endpoint;

class TemplateEndpointEntry
{
    public function __construct(
        private ?string $template = null,
        private ?string $path = null,
        private ?string $routeName = null,
        private ?string $description = null
    )
    {
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
