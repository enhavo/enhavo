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
    private $entityName;

    /**
     * @var string
     */
    private $bundleName;

    /**
     * @var string
     */
    private $humanizedBundleName;

    /**
     * @var PropertyNode[]
     */
    private $properties = [];

    /**
     * @var array
     */
    private $filters = [];

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
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
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
     * @return string
     */
    public function getHumanizedBundleName()
    {
        return $this->humanizedBundleName;
    }

    /**
     * @param string $humanizedBundleName
     */
    public function setHumanizedBundleName($humanizedBundleName)
    {
        $this->humanizedBundleName = $humanizedBundleName;
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

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filter
     */
    public function setFilters($filter)
    {
        $this->filters = $filter;
    }
}