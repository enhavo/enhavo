<?php
namespace esperanto\ContentBundle\Item\Type;

use Doctrine\Common\Collections\ArrayCollection;
use esperanto\MediaBundle\Entity\File;

class Contact
{
    /**
     * @var string
     */
    private $text;
    private $title;
    private $type;
    private $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }
    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setFiles($files)
    {
        foreach($files as $file) {
            $newFile = new File();
            $newFile->setId($file);
            $this->files->add($newFile);
        }
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }
}