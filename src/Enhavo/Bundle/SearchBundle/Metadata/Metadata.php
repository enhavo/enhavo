<?php


namespace Enhavo\Bundle\SearchBundle\Metadata;

/**
 * Metadata.php
 *
 * @since 23/06/16
 * @author gseidel
 */
class Metadata
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $bundleName;

    /**
     * @var PropertyNode[]
     */
    private $properties = [];

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return $this->bundleName;
    }

    /**
     * @param string $bundleName
     */
    public function setBundleName($bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * @return PropertyNode[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }
}