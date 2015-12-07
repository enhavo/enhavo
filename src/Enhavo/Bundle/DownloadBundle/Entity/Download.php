<?php

namespace Enhavo\Bundle\DownloadBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Download
 */
class Download
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
     * @var \Enhavo\Bundle\MediaBundle\Entity\File
     */
    protected $file;

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
     * @param \Enhavo\Bundle\MediaBundle\Entity\File file
     * @return Page
     */
    public function addFile(\Enhavo\Bundle\MediaBundle\Entity\File $file)
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
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $file
     */
    public function removeFile(\Enhavo\Bundle\MediaBundle\Entity\File $file)
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
