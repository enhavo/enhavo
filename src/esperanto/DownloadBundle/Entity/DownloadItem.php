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
}
