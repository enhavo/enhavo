<?php


namespace Enhavo\Bundle\SearchBundle\Metadata;

/**
 * Metadata.php
 *
 * @since 23/06/16
 * @author gseidel
 */
class Metadata extends \Enhavo\Component\Metadata\Metadata
{
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
     * @param PropertyNode $property
     */
    public function addProperty(PropertyNode $property)
    {
        $this->properties[] = $property;
    }

    /**
     * @return Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param Filter $filter
     */
    public function addFilter($filter)
    {
        $this->filters[] = $filter;
    }
}
