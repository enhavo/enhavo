<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EventListener;

class ReferenceChange
{
    /** @var object */
    private $entity;

    /** @var string */
    private $property;

    private $value;

    /**
     * ReferenceChange constructor.
     */
    public function __construct(object $entity, string $property, $value)
    {
        $this->entity = $entity;
        $this->property = $property;
        $this->value = $value;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getValue()
    {
        return $this->value;
    }
}
