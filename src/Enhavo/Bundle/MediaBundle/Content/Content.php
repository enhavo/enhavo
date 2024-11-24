<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 10:29
 */

namespace Enhavo\Bundle\MediaBundle\Content;


class Content extends AbstractContent
{
    /**
     * @var
     */
    private $path;

    public function __construct(string $content = null)
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'Content');
        file_put_contents($tempPath, $content ?? '');
        $this->path = $tempPath;
    }

    public function getContent()
    {
        return file_get_contents($this->path);
    }

    public function getFilePath()
    {
        return $this->path;
    }

    public function __destruct()
    {
        if(file_exists($this->path)) {
            unlink($this->path);
        }
    }
}
