<?php
/**
 * PictureType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ContentGridBundle\Item\Type;


use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\MediaBundle\Entity\File;

class Picture
{
    /**
     * @var array
     */
    private $files;

    private $title;

    /**
     * @var string
     */
    private $type;

    public function __construct()
    {
        $this->files = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
