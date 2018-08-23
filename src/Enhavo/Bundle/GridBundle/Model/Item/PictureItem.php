<?php

namespace Enhavo\Bundle\GridBundle\Model\Item;

use Enhavo\Bundle\GridBundle\Entity\AbstractItem;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * Picture
 */
class PictureItem extends AbstractItem
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var FileInterface
     */
    private $file;

    /**
     * Set title
     *
     * @param string $title
     * @return PictureItem
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
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param mixed $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * Set file
     *
     * @param FileInterface|null $file
     * @return PictureItem
     */
    public function setFile(FileInterface $file = null)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return FileInterface
     */
    public function getFile()
    {
        return $this->file;
    }
}
