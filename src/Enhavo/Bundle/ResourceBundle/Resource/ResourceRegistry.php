<?php

namespace Enhavo\Bundle\ResourceBundle\Resource;

class ResourceRegistry
{
    public function __construct(
        private readonly array $resources
    )
    {
    }

    public function getMetadata($name)
    {

    }
}
