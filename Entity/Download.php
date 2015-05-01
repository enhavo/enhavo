<?php

namespace esperanto\DownloadBundle\Entity;

use esperanto\MediaBundle\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;
use BaconStringUtils\Slugifier;

use Doctrine\ORM\Mapping as ORM;

/**
 * Download
 */
abstract class Download
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

    private $file;


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
     * @return Download
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
     * Set teaser
     *
     * @param string $teaser
     * @return Download
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get teaser
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \file $file
     * @return Download
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Add file
     *
     * @param \esperanto\MediaBundle\Entity\File file
     * @return Page
     */
    public function addFile(\esperanto\MediaBundle\Entity\File $file)
    {
        if($this->file === null) {
            $this->file = new ArrayCollection();
        }

        $this->file[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \esperanto\MediaBundle\Entity\File $file
     */
    public function removeFile(\esperanto\MediaBundle\Entity\File $file)
    {
        $this->file->removeElement($file);
    }

    /**
     * Get file
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFile()
    {
        return $this->file;
    }
}
