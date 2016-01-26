<?php

namespace Enhavo\Bundle\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Index
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
     * @var string
     */
    protected $type;

    /**
     * @var integer
     */
    protected $score;

    /**
     * @var \Enhavo\Bundle\SearchBundle\Entity\Index
     */
    private $dataset;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Set dataset
     *
     * @param \Enhavo\Bundle\SearchBundle\Entity\Index $dataset
     *
     * @return Index
     */
    public function setDataset(\Enhavo\Bundle\SearchBundle\Entity\Index $dataset = null)
    {
        $this->dataset = $dataset;

        return $this;
    }

    /**
     * Get dataset
     *
     * @return \Enhavo\Bundle\SearchBundle\Entity\Index
     */
    public function getDataset()
    {
        return $this->dataset;
    }
}
