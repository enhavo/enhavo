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

class TemplateEndpointEntry
{
    public function __construct(
        private ?string $template = null,
        private ?string $path = null,
        private ?string $routeName = null,
        private ?string $description = null,
        private ?array $variants = null,
    ) {
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

    public function getVariants(): array
    {
        $variants = [];
        if (is_array($this->variants)) {
            foreach ($this->variants as $condition => $variant) {
                $variants[$condition] = $variant['description'] ?? null;
            }
        }

        return $variants;
    }
}
