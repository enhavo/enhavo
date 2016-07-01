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