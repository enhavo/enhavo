<?php
/**
 * Metadata.php
 *
 * @since 05/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Metadata;

use Enhavo\Bundle\AppBundle\Util\NameTransformer;

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
     * @var NameTransformer
     */
    private $nameTransformer;

    public function __construct()
    {
        $this->nameTransformer = new NameTransformer();
    }

    /**
     * @return PropertyNode[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string $property
     * @return PropertyNode|null
     */
    public function getProperty(string $property)
    {
        $name = $this->nameTransformer->camelCase($property, true);
        if(isset($this->properties[$name])) {
            return $this->properties[$name];
        }
        return null;
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
