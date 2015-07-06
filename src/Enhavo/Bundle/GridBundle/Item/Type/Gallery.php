<?php
namespace Enhavo\Bundle\ContentGridBundle\Item\Type;

use Doctrine\Common\Collections\ArrayCollection;

class Gallery
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    private $files;

    private $type;


    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Gallery
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * Set text
     *
     * @param string $text
     *
     * @return Gallery
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
     * @param array $files
     */
    public function setFiles($files)
    {
        foreach($files as $file) {
            $newFile = new File();
            $newFile->setId($file['id']);
            $newFile->setOrder($file['order']);
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