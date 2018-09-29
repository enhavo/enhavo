<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 10:29
 */

namespace Enhavo\Bundle\MediaBundle\Content;


use Enhavo\Bundle\MediaBundle\Exception\FileException;

class PathContent extends AbstractContent
{
    /**
     * @var
     */
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getContent()
    {
        if(!file_exists($this->path)) {
            throw new FileException(sprintf('File could not be found on path "%s"', $this->path));
        }
        return file_get_contents($this->path);
    }

    public function getFilePath()
    {
        return $this->path;
    }
}