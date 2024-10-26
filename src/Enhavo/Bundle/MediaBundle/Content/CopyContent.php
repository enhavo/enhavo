<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 27.08.17
 * Time: 10:29
 */

namespace Enhavo\Bundle\MediaBundle\Content;

use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;

class CopyContent extends AbstractContent
{
    private string $path;

    public function __construct(ContentInterface $content)
    {
        $this->path = tempnam(sys_get_temp_dir(), 'Content');
        (new Filesystem())->copy($content->getFilePath(), $this->path, true);
    }

    public function getContent()
    {
        if (!file_exists($this->path)) {
            throw new FileException(sprintf('File could not be found on path "%s"', $this->path));
        }
        return file_get_contents($this->path);
    }

    public function getFilePath()
    {
        return $this->path;
    }
}
