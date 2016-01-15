<?php

namespace Enhavo\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 */
class File
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var integer
     */
    protected $order;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var bool
     */
    protected $garbage;

    /**
     * @var \DateTime
     */
    protected $garbageTimestamp;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return File
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    public function __equals()
    {

    }

    /**
     * Set order
     *
     * @param integer $order
     * @return File
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return File
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isGarbage()
    {
        return $this->garbage;
    }

    /**
     * @param boolean $garbage
     * @param \DateTime $garbageTimestamp
     *
     * @return File
     */
    public function setGarbage($garbage, \DateTime $garbageTimestamp = null)
    {
        $this->garbage = $garbage;

        if ($garbageTimestamp == null)
        {
            $garbageTimestamp = new \DateTime();
        }
        $this->setGarbageTimestamp($garbageTimestamp);

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getGarbageTimestamp()
    {
        return $this->garbageTimestamp;
    }

    /**
     * @param \DateTime $garbageTimestamp
     *
     * @return File
     */
    public function setGarbageTimestamp($garbageTimestamp)
    {
        $this->garbageTimestamp = $garbageTimestamp;

        return $this;
    }
}
