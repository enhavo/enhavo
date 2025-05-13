<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Model\Block;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * TextPicture
 */
class TextPictureBlock extends AbstractBlock
{
    public const LAYOUT_1_1 = 0;
    public const LAYOUT_1_2 = 1;
    public const LAYOUT_2_1 = 2;

    private ?string $text = null;
    private ?string $title = null;
    private bool $textLeft = false;
    private ?FileInterface $file = null;
    private bool $float = false;
    private ?string $caption = null;
    private ?int $layout = self::LAYOUT_1_1;

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
     * @param bool $textLeft
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
     * @return bool
     */
    public function getTextLeft()
    {
        return $this->textLeft;
    }

    /**
     * Set file
     *
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
     * @param bool $float
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
     * @return bool
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
