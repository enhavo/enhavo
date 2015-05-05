<?php
/**
 * PictureType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Item\Type;


use Doctrine\Common\Collections\ArrayCollection;
use esperanto\MediaBundle\Entity\File;

class Picture
{
    /**
     * @var array
     */
    private $files;

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
