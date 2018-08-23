<?php

namespace Enhavo\Bundle\GridBundle\Model\Item;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;
use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * Gallery
 */
class GalleryItem extends AbstractItem
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var ArrayCollection
     */
    private $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return GalleryItem
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
     * @return GalleryItem
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
     * Add files
     *
     * @param FileInterface $file
     * @return GalleryItem
     */
    public function addFiles(FileInterface $file)
    {
        if ($this->files === null) {
            $this->files = new ArrayCollection();
        }

        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove files
     *
     * @param FileInterface $file
     */
    public function removeFiles(FileInterface $file)
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
     * Add file
     *
     * @param FileInterface $file
     *
     * @return GalleryItem
     */
    public function addFile(FileInterface $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param FileInterface $file
     */
    public function removeFile(FileInterface $file)
    {
        $this->files->removeElement($file);
    }
}
