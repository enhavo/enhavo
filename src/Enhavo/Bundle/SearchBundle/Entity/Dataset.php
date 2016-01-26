<?php

namespace Enhavo\Bundle\SearchBundle\Entity;

/**
 * Dataset
 */
class Dataset
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $reindex;

    /**
     * @var integer
     */
    private $reference;


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
     * Set type
     *
     * @param string $type
     *
     * @return Dataset
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set reindex
     *
     * @param integer $reindex
     *
     * @return Dataset
     */
    public function setReindex($reindex)
    {
        $this->reindex = $reindex;

        return $this;
    }

    /**
     * Get reindex
     *
     * @return integer
     */
    public function getReindex()
    {
        return $this->reindex;
    }

    /**
     * Set reference
     *
     * @param integer $reference
     *
     * @return Dataset
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return integer
     */
    public function getReference()
    {
        return $this->reference;
    }
}
