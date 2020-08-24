<?php

namespace Enhavo\Bundle\TranslationBundle\Entity;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * Class TranslationFile
 * @package Enhavo\Bundle\TranslationBundle\Entity
 */
class TranslationFile
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $class;

    /**
     * @var integer
     */
    private $refId;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var FileInterface|null
     */
    private $file;

    /**
     * @var string
     */
    private $property;

    /**
     * @var mixed
     */
    private $object;

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
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return int
     */
    public function getRefId()
    {
        return $this->refId;
    }

    /**
     * @param int $refId
     */
    public function setRefId($refId)
    {
        $this->refId = $refId;
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
     * @return FileInterface|null
     */
    public function getFile(): ?FileInterface
    {
        return $this->file;
    }

    /**
     * @param FileInterface|null $file
     */
    public function setFile(?FileInterface $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }
}
