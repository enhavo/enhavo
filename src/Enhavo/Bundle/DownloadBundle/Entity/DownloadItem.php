<?php

namespace Enhavo\Bundle\DownloadBundle\Entity;

use Enhavo\Bundle\DownloadBundle\Model\DownloadInterface;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

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
     * @var DownloadInterface
     */
    protected $download;

    /**
     * @var FileInterface
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
     * @param DownloadInterface $download
     *
     * @return DownloadItem
     */
    public function setDownload(DownloadInterface $download = null)
    {
        $this->download = $download;

        return $this;
    }

    /**
     * Get download
     *
     * @return DownloadInterface
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * @param FileInterface|null $file
     * @return DownloadItem
     */
    public function setFile(FileInterface $file = null)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return FileInterface|null
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
