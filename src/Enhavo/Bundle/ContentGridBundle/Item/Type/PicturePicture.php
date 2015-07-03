<?php

namespace Enhavo\Bundle\ContentGridBundle\Item\Type;


use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\MediaBundle\Entity\File;

class PicturePicture
{
    /**
     * @var array
     */
    private $filesLeft;
    private $filesRight;
    /**
     * @var string
     */
    private $type;

    public function __construct()
    {
        $this->filesLeft = new ArrayCollection();
        $this->filesRight = new ArrayCollection();
    }

    /**
     * @param array $filesLeft
     */
    public function setFilesLeft($filesLeft)
    {
        foreach($filesLeft as $file) {
            $newFile = new File();
            $newFile->setId($file);
            $this->filesLeft->add($newFile);
        }
    }

    /**
     * @return array
     */
    public function getFilesLeft()
    {
        return $this->filesLeft;
    }

    /**
     * @param array $filesRight
     */
    public function setFilesRight($filesRight)
    {
        foreach($filesRight as $file) {
            $newFile = new File();
            $newFile->setId($file);
            $this->filesRight->add($newFile);
        }
    }

    /**
     * @return array
     */
    public function getFilesRight()
    {
        return $this->filesRight;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}