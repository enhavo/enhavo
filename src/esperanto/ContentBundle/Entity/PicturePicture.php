<?php

namespace esperanto\ContentBundle\Entity;

use esperanto\MediaBundle\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;
use esperanto\ContentBundle\Item\ItemTypeInterface;

/**
 * PicturePicture
 */
class PicturePicture implements ItemTypeInterface
{
    /**
     * @var integer
     */
    private $id;

    private $filesLeft;

    private $filesRight;

    private $frame;

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
     * @param \esperanto\MediaBundle\Entity\File filesLeft
     * @return Content
     */
    public function addFilesLeft(\esperanto\MediaBundle\Entity\File $filesLeft)
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
     * @param \esperanto\MediaBundle\Entity\File $filesLeft
     */
    public function removeFilesLeft(\esperanto\MediaBundle\Entity\File $filesLeft)
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
     * @param \esperanto\MediaBundle\Entity\File filesRight
     * @return Content
     */
    public function addFilesRight(\esperanto\MediaBundle\Entity\File $filesRight)
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
     * @param \esperanto\MediaBundle\Entity\File $filesRight
     */
    public function removeFilesRight(\esperanto\MediaBundle\Entity\File $filesRight)
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
     * Constructor
     */
    public function __construct()
    {
        $this->filesLeft = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filesRight = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
