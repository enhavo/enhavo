<?php

namespace Enhavo\Bundle\SearchBundle\Model\Database;

/**
 * IndexData
 */
class Index
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $word;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var integer
     */
    protected $weight;

    /**
     * @var float
     */
    protected $score = 0;

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
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param string $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
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
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param float $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }
}
