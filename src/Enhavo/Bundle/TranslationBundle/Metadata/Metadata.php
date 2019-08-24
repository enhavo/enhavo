<?php
/**
 * Metadata.php
 *
 * @since 05/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Metadata;


class Metadata
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var PropertyNode[]
     */
    private $properties = [];

    /**
     * @return PropertyNode[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param PropertyNode[] $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    public function addProperty(PropertyNode $property)
    {
        $this->properties[] = $property;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
}
