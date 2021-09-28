<?php

namespace Enhavo\Bundle\DoctrineExtensionBundle\EventListener;

class ReferenceChange
{
    /** @var object */
    private $entity;

    /** @var string */
    private $property;

    /** @var mixed */
    private $value;

    /**
     * ReferenceChange constructor.
     * @param object $entity
     * @param string $property
     * @param mixed $value
     */
    public function __construct(object $entity, string $property, $value)
    {
        $this->entity = $entity;
        $this->property = $property;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return object
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
