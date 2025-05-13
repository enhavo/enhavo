<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Endpoint\Template;

class TemplateEndpointFilter
{
    public function __construct(
        private ?string $fulltext = null,
        private ?string $template = null,
        private ?string $path = null,
        private ?string $routeName = null,
        private ?string $description = null,
    ) {
    }

    public function getFulltext(): ?string
    {
        return $this->fulltext;
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
