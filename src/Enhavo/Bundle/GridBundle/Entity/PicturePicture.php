<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\MediaBundle\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

/**
 * PicturePicture
 */
class PicturePicture implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Enhavo\Bundle\MediaBundle\Entity\File
     */
    protected $fileLeft;

    /**
     * @var \Enhavo\Bundle\MediaBundle\Entity\File
     */
    protected $fileRight;

    /**
     * @var boolean
     */
    protected $frame;

    /**
     * @var string
     */
    protected $captionLeft;

    /**
     * @var string
     */
    protected $captionRight;

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
     * Set fileLeft
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File|null $fileLeft
     * @return Content
     */
    public function setFileLeft($fileLeft)
    {
        $this->fileLeft = $fileLeft;

        return $this;
    }

    /**
     * Get fileLeft
     *
     * @return \Enhavo\Bundle\MediaBundle\Entity\File|null
     */
    public function getFileLeft()
    {
        return $this->fileLeft;
    }

    /**
     * Set fileRight
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $fileRight
     * @return Content
     */
    public function setFileRight($fileRight)
    {
        $this->fileRight = $fileRight;

        return $this;
    }

    /**
     * Get fileRight
     *
     * @return \Enhavo\Bundle\MediaBundle\Entity\File
     */
    public function getFileRight()
    {
        return $this->fileRight;
    }

    /**
     * @return mixed
     */
    public function getFrame()
    {
        return $this->frame;
    }

    /**
     * @param mixed $frame
     */
    public function setFrame($frame)
    {
        $this->frame = $frame;
    }

    /**
     * @return mixed
     */
    public function getCaptionLeft()
    {
        return $this->captionLeft;
    }

    /**
     * @param mixed $captionLeft
     */
    public function setCaptionLeft($captionLeft)
    {
        $this->captionLeft = $captionLeft;
    }

    /**
     * @return mixed
     */
    public function getCaptionRight()
    {
        return $this->captionRight;
    }

    /**
     * @param mixed $captionRight
     */
    public function setCaptionRight($captionRight)
    {
        $this->captionRight = $captionRight;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
