<?php

namespace Enhavo\Bundle\DownloadBundle\Entity;

use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

/**
 * DownloadItem
 */
class DownloadItem implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Enhavo\Bundle\DownloadBundle\Entity\Download
     */
    protected $download;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $file;

    /**
     * @var string
     */
    protected $title;

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
     * @param \Enhavo\Bundle\DownloadBundle\Entity\Download $download
     *
     * @return DownloadItem
     */
    public function setDownload(\Enhavo\Bundle\DownloadBundle\Entity\Download $download = null)
    {
        $this->download = $download;

        return $this;
    }

    /**
     * Get download
     *
     * @return \Enhavo\Bundle\DownloadBundle\Entity\Download
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
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $file
     *
     * @return DownloadItem
     */
    public function addFile(\Enhavo\Bundle\MediaBundle\Entity\File $file)
    {
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

    /**
     * Set title
     *
     * @param string $title
     *
     * @return DownloadItem
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
}
