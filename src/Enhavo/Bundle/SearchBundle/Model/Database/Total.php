<?php

namespace Enhavo\Bundle\SearchBundle\Model\Database;

/**
 * Total
 */
class Total
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $word;

    /**
     * @var float
     */
    private $count;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set word
     *
     * @param string $word
     *
     * @return Total
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set count
     *
     * @param float $count
     *
     * @return Total
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return float
     */
    public function getCount()
    {
        return $this->count;
    }
}
