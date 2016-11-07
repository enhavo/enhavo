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
     * @var Property[]
     */
    private $properties = [];

    /**
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param Property[] $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
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