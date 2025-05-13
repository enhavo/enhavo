<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate\Metadata;

class Metadata extends \Enhavo\Component\Metadata\Metadata
{
    private array $properties = [];
    private array $class = [];

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    public function getClass(): array
    {
        return $this->class;
    }

    public function setClass(array $class): void
    {
        $this->class = $class;
    }
}
