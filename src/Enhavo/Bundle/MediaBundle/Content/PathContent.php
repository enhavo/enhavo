<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 10:29
 */

namespace Enhavo\Bundle\MediaBundle\Content;


class PathContent implements ContentInterface
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
        return file_get_contents($this->path);
    }

    public function getFilePath()
    {
        return $this->path;
    }
}