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
     * @var mixed
     */
    protected $filesLeft;

    /**
     * @var mixed
     */
    protected $filesRight;

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
     * Constructor
     */
    public function __construct()
    {
        $this->filesLeft = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filesRight = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set filesLeft
     *
     * @param \files $filesLeft
     * @return Content
     */
    public function setFilesLeft($filesLeft)
    {
        $this->filesLeft = $filesLeft;

        return $this;
    }

    /**
     * Add filesLeft
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File filesLeft
     * @return Content
     */
    public function addFilesLeft(\Enhavo\Bundle\MediaBundle\Entity\File $filesLeft)
    {
        if ($this->filesLeft === null) {
            $this->filesLeft = new ArrayCollection();
        }

        $this->filesLeft[] = $filesLeft;

        return $this;
    }

    /**
     * Remove filesLeft
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $filesLeft
     */
    public function removeFilesLeft(\Enhavo\Bundle\MediaBundle\Entity\File $filesLeft)
    {
        $this->filesLeft->removeElement($filesLeft);
    }

    /**
     * Get filesLeft
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilesLeft()
    {
        return $this->filesLeft;
    }

    /**
     * Set filesRight
     *
     * @param \files $filesRight
     * @return Content
     */
    public function setFilesRight($filesRight)
    {
        $this->filesRight = $filesRight;

        return $this;
    }

    /**
     * Add filesRight
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File filesRight
     * @return Content
     */
    public function addFilesRight(\Enhavo\Bundle\MediaBundle\Entity\File $filesRight)
    {
        if ($this->filesRight === null) {
            $this->filesRight = new ArrayCollection();
        }

        $this->filesRight[] = $filesRight;

        return $this;
    }

    /**
     * Remove filesRight
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $filesRight
     */
    public function removeFilesRight(\Enhavo\Bundle\MediaBundle\Entity\File $filesRight)
    {
        $this->filesRight->removeElement($filesRight);
    }

    /**
     * Get filesRight
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilesRight()
    {
        return $this->filesRight;
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
