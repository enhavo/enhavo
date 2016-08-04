<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\MediaBundle\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

/**
 * ThreePicture
 */
class ThreePicture implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $titleLeft;

    /**
     * @var boolean
     */
    protected $titleRight;

    /**
     * @var string
     */
    protected $titleCenter;

    /**
     * @var string
     */
    protected $captionLeft;

    /**
     * @var boolean
     */
    protected $captionRight;

    /**
     * @var string
     */
    protected $captionCenter;

    /**
     * @var FileInterface
     */
    protected $fileLeft;

    /**
     * @var FileInterface
     */
    protected $fileRight;

    /**
     * @var FileInterface
     */
    protected $fileCenter;


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
     * Set titleLeft
     *
     * @param string $titleLeft
     *
     * @return ThreePicture
     */
    public function setTitleLeft($titleLeft)
    {
        $this->titleLeft = $titleLeft;

        return $this;
    }

    /**
     * Get titleLeft
     *
     * @return string
     */
    public function getTitleLeft()
    {
        return $this->titleLeft;
    }

    /**
     * Set titleRight
     *
     * @param boolean $titleRight
     *
     * @return ThreePicture
     */
    public function setTitleRight($titleRight)
    {
        $this->titleRight = $titleRight;

        return $this;
    }

    /**
     * Get titleRight
     *
     * @return boolean
     */
    public function getTitleRight()
    {
        return $this->titleRight;
    }

    /**
     * Set titleCenter
     *
     * @param string $titleCenter
     *
     * @return ThreePicture
     */
    public function setTitleCenter($titleCenter)
    {
        $this->titleCenter = $titleCenter;

        return $this;
    }

    /**
     * Get titleCenter
     *
     * @return string
     */
    public function getTitleCenter()
    {
        return $this->titleCenter;
    }

    /**
     * Set captionLeft
     *
     * @param string $captionLeft
     *
     * @return ThreePicture
     */
    public function setCaptionLeft($captionLeft)
    {
        $this->captionLeft = $captionLeft;

        return $this;
    }

    /**
     * Get captionLeft
     *
     * @return string
     */
    public function getCaptionLeft()
    {
        return $this->captionLeft;
    }

    /**
     * Set captionRight
     *
     * @param boolean $captionRight
     *
     * @return ThreePicture
     */
    public function setCaptionRight($captionRight)
    {
        $this->captionRight = $captionRight;

        return $this;
    }

    /**
     * Get captionRight
     *
     * @return boolean
     */
    public function getCaptionRight()
    {
        return $this->captionRight;
    }

    /**
     * Set captionCenter
     *
     * @param string $captionCenter
     *
     * @return ThreePicture
     */
    public function setCaptionCenter($captionCenter)
    {
        $this->captionCenter = $captionCenter;

        return $this;
    }

    /**
     * Get captionCenter
     *
     * @return string
     */
    public function getCaptionCenter()
    {
        return $this->captionCenter;
    }

    /**
     * Set fileLeft
     *
     * @param FileInterface $fileLeft
     *
     * @return ThreePicture
     */
    public function setFileLeft(FileInterface $fileLeft = null)
    {
        $this->fileLeft = $fileLeft;

        return $this;
    }

    /**
     * Get fileLeft
     *
     * @return FileInterface
     */
    public function getFileLeft()
    {
        return $this->fileLeft;
    }

    /**
     * Set fileRight
     *
     * @param FileInterface $fileRight
     *
     * @return ThreePicture
     */
    public function setFileRight(FileInterface $fileRight = null)
    {
        $this->fileRight = $fileRight;

        return $this;
    }

    /**
     * Get fileRight
     *
     * @return FileInterface
     */
    public function getFileRight()
    {
        return $this->fileRight;
    }

    /**
     * Set fileCenter
     *
     * @param FileInterface $fileCenter
     *
     * @return ThreePicture
     */
    public function setFileCenter(FileInterface $fileCenter = null)
    {
        $this->fileCenter = $fileCenter;

        return $this;
    }

    /**
     * Get fileCenter
     *
     * @return FileInterface
     */
    public function getFileCenter()
    {
        return $this->fileCenter;
    }
}
