<?php

namespace esperanto\DownloadBundle\Entity;

use esperanto\ContentBundle\Item\ItemTypeInterface;

/**
 * DownloadItem
 */
class DownloadItem implements ItemTypeInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \esperanto\DownloadBundle\Entity\Download
     */
    private $download;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
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
     * Set download
     *
     * @param \esperanto\DownloadBundle\Entity\Download $download
     *
     * @return DownloadItem
     */
    public function setDownload(\esperanto\DownloadBundle\Entity\Download $download = null)
    {
        $this->download = $download;

        return $this;
    }

    /**
     * Get download
     *
     * @return \esperanto\DownloadBundle\Entity\Download
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->file = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add file
     *
     * @param \esperanto\MediaBundle\Entity\File $file
     *
     * @return DownloadItem
     */
    public function addFile(\esperanto\MediaBundle\Entity\File $file)
    {
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
