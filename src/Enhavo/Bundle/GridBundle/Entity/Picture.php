<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Picture
 */
class Picture implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    protected $caption;

    /**
     * @var \Enhavo\Bundle\MediaBundle\Entity\File
     */
    protected $file;


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
     * Set title
     *
     * @param string $title
     * @return Picture
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
     * @param \Enhavo\Bundle\MediaBundle\Entity\File|null $file
     * @return Grid
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \Enhavo\Bundle\MediaBundle\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }
}
