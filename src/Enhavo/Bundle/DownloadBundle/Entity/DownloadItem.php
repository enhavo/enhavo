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
     * @var \Enhavo\Bundle\MediaBundle\Entity\File
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
     * @param \Enhavo\Bundle\MediaBundle\Entity\File|null $file
     * @return DownloadItem
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return \Enhavo\Bundle\MediaBundle\Entity\File|null
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
