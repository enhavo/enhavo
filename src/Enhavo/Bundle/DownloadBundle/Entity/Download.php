<?php

namespace Enhavo\Bundle\DownloadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\DownloadBundle\Model\DownloadInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Download
 */
class Download implements DownloadInterface, ResourceInterface
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
     * @var FileInterface
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
     * @param string $text
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
     * @param FileInterface|null $file
     * @return Download
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
}
