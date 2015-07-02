<?php

namespace enhavo\ContentBundle\Entity;

use enhavo\ContentBundle\Item\ItemTypeInterface;
use enhavo\MediaBundle\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Gallery
 */
class Gallery implements ItemTypeInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    private $files;


    public function __construct()
    {
        $this->files = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Gallery
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
     * Set text
     *
     * @param string $text
     *
     * @return Gallery
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set files
     *
     * @param \files $files
     * @return Content
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Add files
     *
     * @param \enhavo\MediaBundle\Entity\File files
     * @return Content
     */
    public function addFiles(\enhavo\MediaBundle\Entity\File $files)
    {
        if ($this->files === null) {
            $this->files = new ArrayCollection();
        }

        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param \enhavo\MediaBundle\Entity\File $files
     */
    public function removeFiles(\enhavo\MediaBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
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
     * Add file
     *
     * @param \enhavo\MediaBundle\Entity\File $file
     *
     * @return Gallery
     */
    public function addFile(\enhavo\MediaBundle\Entity\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \enhavo\MediaBundle\Entity\File $file
     */
    public function removeFile(\enhavo\MediaBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }
}
