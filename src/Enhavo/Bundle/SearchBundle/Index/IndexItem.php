<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 29.06.16
 * Time: 16:39
 */

namespace Enhavo\Bundle\SearchBundle\Index;


class IndexItem {

    /**
     * @var string
     */
    private $rawData;

    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var string
     */
    private $weight;

    /**
     * @var string
     */
    private $data;

    /**
     * @var array
     */
    private $scoredWords;

    /**
     * @return string
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @param string $rawData
     */
    public function setRawData($rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param string $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getScoredWords()
    {
        return $this->scoredWords;
    }

    /**
     * @param array $scoredWords
     */
    public function setScoredWords($scoredWords)
    {
        $this->scoredWords = $scoredWords;
    }
}