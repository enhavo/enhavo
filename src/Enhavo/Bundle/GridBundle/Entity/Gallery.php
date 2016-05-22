<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * Gallery
 */
class Gallery implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var ArrayCollection
     */
    protected $files;

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
     * Add files
     *
     * @param FileInterface $file
     * @return Gallery
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
     * @return Gallery
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
