<?php

namespace Enhavo\Bundle\MediaBundle\Entity;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * File
 */
class File implements FileInterface
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
     * @var array
     */
    protected $parameters;

    /**
     * @var bool
     */
    protected $garbage;

    /**
     * @var \DateTime
     */
    protected $garbageTimestamp;

    /**
     * @var ContentInterface
     */
    protected $content;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $md5Checksum;

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
     * Sets the parameter $key to value $value
     *
     * @param string $key
     * @param string $value
     * @return File
     */
    public function setParameter($key, $value)
    {
        if (!$this->parameters) {
            $this->parameters = array();
        }

        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * Returns the parameter value for $key.
     * If no value is set for $key, returns null.
     *
     * @param string $key
     * @return string|null
     */
    public function getParameter($key)
    {
        if (!$this->parameters) {
            $this->parameters = array();
        }

        return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        if (!$this->parameters) {
            $this->parameters = array();
        }

        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return File
     */
    public function setParameters($parameters)
    {
        if(is_array($this->parameters) && is_array($parameters)) {
            $this->parameters = array_merge($this->parameters, $parameters);
        } else {
            $this->parameters = $parameters;
        }
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

    /**
     * Returns if this file is an image based on the mime type
     *
     * @return bool
     */
    public function isImage()
    {
        return strtolower(substr($this->getMimeType(), 0, 5)) == 'image';
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return ContentInterface
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param ContentInterface $content
     */
    public function setContent(ContentInterface $content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getMd5Checksum()
    {
        return $this->md5Checksum;
    }

    /**
     * @param string $md5Checksum
     */
    public function setMd5Checksum($md5Checksum)
    {
        $this->md5Checksum = $md5Checksum;
    }
}
