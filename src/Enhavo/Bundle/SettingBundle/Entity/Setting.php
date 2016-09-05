<?php

namespace Enhavo\Bundle\SettingBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

class Setting implements ResourceInterface
{

    const SETTING_TYPE_TEXT = 'text';
    const SETTING_TYPE_BOOLEAN = 'boolean';
    const SETTING_TYPE_FILE = 'file';
    const SETTING_TYPE_FILES = 'files';
    const SETTING_TYPE_WYSIWYG = 'wysiwyg';

    protected $id;
    protected $label;
    protected $key;
    protected $type;
    protected $value;
    protected $translationDomain;
    protected $file;            // pointing to one file
    protected $files;           // pointing to a collection of files
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set label
     *
     * @param string $label
     *
     * @return Setting
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return Setting
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Setting
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
     * Set value
     *
     * @param string $value
     *
     * @return Setting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set file
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $file
     *
     * @return Setting
     */
    public function setFile(\Enhavo\Bundle\MediaBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \Enhavo\Bundle\MediaBundle\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Add file
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $file
     *
     * @return Setting
     */
    public function addFile(\Enhavo\Bundle\MediaBundle\Entity\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $file
     */
    public function removeFile(\Enhavo\Bundle\MediaBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }


    /**
     * Set translationDomain
     *
     * @param string $translationDomain
     *
     * @return Setting
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    /**
     * Get translationDomain
     *
     * @return string
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }
}
