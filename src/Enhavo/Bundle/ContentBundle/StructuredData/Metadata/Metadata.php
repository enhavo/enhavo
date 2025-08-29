<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData\Metadata;

class Metadata extends \Enhavo\Component\Metadata\Metadata
{
    private array $classes = [];
    private array $properties = [];
    private array $methods = [];

    public function getClasses(): array
    {
        return $this->classes;
    }

    public function setClasses(array $classes): void
    {
        $this->classes = $classes;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }
}
