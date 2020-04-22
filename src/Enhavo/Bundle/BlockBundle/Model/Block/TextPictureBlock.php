<?php

namespace Enhavo\Bundle\BlockBundle\Model\Block;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * TextPicture
 */
class TextPictureBlock extends AbstractBlock
{
    const LAYOUT_1_1 = 0;
    const LAYOUT_1_2 = 1;
    const LAYOUT_2_1 = 2;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $title;

    /**
     * @var boolean
     */
    private $textLeft;

    /**
     * @var FileInterface|null
     */
    private $file;

    /**
     * @var boolean
     */
    private $float;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var integer
     */
    private $layout;

    /**
     * Set text
     *
     * @param string $text
     *
     * @return TextPictureBlock
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return TextPictureBlock
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
     * Set if text is displayed left, otherwise it will be right
     *
     * @param boolean $textLeft
     *
     * @return TextPictureBlock
     */
    public function setTextLeft($textLeft)
    {
        $this->textLeft = $textLeft;

        return $this;
    }

    /**
     * Get if text is left
     *
     * @return boolean
     */
    public function getTextLeft()
    {
        return $this->textLeft;
    }

    /**
     * Set file
     *
     * @param FileInterface|null $file
     * @return TextPictureBlock
     */
    public function setFile(?FileInterface $file)
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
     * Set float
     *
     * @param boolean $float
     *
     * @return TextPictureBlock
     */
    public function setFloat($float)
    {
        $this->float = $float;

        return $this;
    }

    /**
     * Get float
     *
     * @return boolean
     */
    public function getFloat()
    {
        return $this->float;
    }

    /**
     * Set caption
     *
     * @param string $caption
     *
     * @return TextPictureBlock
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return int
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param int $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
}
