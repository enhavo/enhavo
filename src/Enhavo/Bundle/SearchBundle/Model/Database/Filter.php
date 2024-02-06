<?php

namespace Enhavo\Bundle\SearchBundle\Model\Database;

/**
 * IndexData
 */
class Filter
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var DataSet
     */
    private $dataSet;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DataSet
     */
    public function getDataSet()
    {
        return $this->dataSet;
    }

    /**
     * @param DataSet $dataSet
     */
    public function setDataSet($dataSet)
    {
        $this->dataSet = $dataSet;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
