<?php

namespace enhavo\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use enhavo\ContentBundle\Item\ItemTypeInterface;
use enhavo\MediaBundle\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Picture
 */
class Picture implements ItemTypeInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    private $caption;

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
     * @return Picture
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
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param mixed $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
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
     * @return Picture
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
