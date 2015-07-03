<?php

namespace Enhavo\Bundle\ContentGridBundle\Entity;

use Enhavo\Bundle\MediaBundle\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\ContentGridBundle\Item\ItemTypeInterface;

/**
 * TextPicture
 */
class TextPicture implements ItemTypeInterface
{
    /**
     * @var integer
     */
    private $id;

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

    private $files;

    private $frame;

    public function __construct()
    {
        $this->files = new ArrayCollection();
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
     * Set text
     *
     * @param string $text
     *
     * @return TextPicture
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
     * @return TextPicture
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
     * Set textleft
     *
     * @param boolean $textLeft
     *
     * @return TextPicture
     */
    public function setTextLeft($textLeft)
    {
        $this->textLeft = $textLeft;

        return $this;
    }

    /**
     * Get textleft
     *
     * @return boolean
     */
    public function getTextLeft()
    {
        return $this->textLeft;
    }

    /**
     * Set files
     *
     * @param \files $files
     * @return Content
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Add files
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File files
     * @return Content
     */
    public function addFiles(\Enhavo\Bundle\MediaBundle\Entity\File $files)
    {
        if ($this->files === null) {
            $this->files = new ArrayCollection();
        }

        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $files
     */
    public function removeFiles(\Enhavo\Bundle\MediaBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
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
     * Add file
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $file
     *
     * @return TextPicture
     */
    public function addFile(\Enhavo\Bundle\MediaBundle\Entity\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \Enhavo\Bundle\MediaBundle\Entity\File $file
     */
    public function removeFile(\Enhavo\Bundle\MediaBundle\Entity\File $file)
    {
        $this->files->removeElement($file);
    }
}
