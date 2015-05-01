<?php
/**
 * Text.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Item\Type;

use Doctrine\Common\Collections\ArrayCollection;
use esperanto\MediaBundle\Entity\File;

class TextPicture
{
    /**
     * @var string
     */
    private $text;

    private $title;

    private $type;
    private $files;
    private $textleft;

    /**
     * Set sticky
     *
     * @param boolean $sticky
     * @return News
     */
    public function setTextleft($textleft)
    {
        $this->textleft = $textleft;

        return $this;
    }

    /**
     * Get sticky
     *
     * @return boolean
     */
    public function getTextleft()
    {
        if($this->textleft === null) {
            return false;
        }

        return $this->textleft;
    }


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