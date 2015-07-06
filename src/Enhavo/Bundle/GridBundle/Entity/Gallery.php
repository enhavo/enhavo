<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
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
     * @param \Enhavo\Bundle\MediaBundle\Entity\File files
     * @return Content
     */
    public function addFiles(\Enhavo\Bundle\MediaBundle\Entity\File $files)
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
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $files
     */
    public function removeFiles(\Enhavo\Bundle\MediaBundle\Entity\File $files)
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
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $file
     *
     * @return Gallery
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
}
