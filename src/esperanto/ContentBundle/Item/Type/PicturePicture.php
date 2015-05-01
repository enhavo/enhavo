<?php

namespace esperanto\ContentBundle\Item\Type;


use Doctrine\Common\Collections\ArrayCollection;
use esperanto\MediaBundle\Entity\File;

class PicturePicture
{
    /**
     * @var array
     */
    private $files1;
    private $files2;
    /**
     * @var string
     */
    private $type;

    public function __construct()
    {
        $this->files1 = new ArrayCollection();
        $this->files2 = new ArrayCollection();
    }

    /**
     * @param array $files1
     */
    public function setFiles1($files1)
    {
        foreach($files1 as $file) {
            $newFile = new File();
            $newFile->setId($file);
            $this->files1->add($newFile);
        }
    }

    /**
     * @return array
     */
    public function getFiles1()
    {
        return $this->files1->toArray();
    }

    /**
     * @param array $files2
     */
    public function setFiles2($files2)
    {
        foreach($files2 as $file) {
            $newFile = new File();
            $newFile->setId($file);
            $this->files2->add($newFile);
        }
    }

    /**
     * @return array
     */
    public function getFiles2()
    {
        return $this->files2->toArray();
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