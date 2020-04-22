<?php

namespace Enhavo\Bundle\BlockBundle\Model\Block;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * Picture
 */
class PictureBlock extends AbstractBlock
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
     * @var FileInterface|null
     */
    private $file;

    /**
     * Set title
     *
     * @param string $title
     * @return PictureBlock
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
     * @return PictureBlock
     */
    public function setFile(?FileInterface $file = null)
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
