<?php
/**
 * Setting.php
 *
 * @since 30/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Model;


class FormatSetting
{
    /**
     * @var int|null
     */
    private $height;

    /**
     * @var int|null
     */
    private $width;

    /**
     * @var int|null
     */
    private $maxHeight;

    /**
     * @var int|null
     */
    private $maxWidth;

    /**
     * @return int|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int|null $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int|null
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * @param int|null $maxHeight
     */
    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;
    }

    /**
     * @return int|null
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * @param int|null $maxWidth
     */
    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;
    }
}