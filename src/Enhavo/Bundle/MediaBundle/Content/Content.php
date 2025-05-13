<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Content;

class Content extends AbstractContent
{
    private $path;

    public function __construct(?string $content = null)
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
        if (file_exists($this->path)) {
            unlink($this->path);
        }
    }
}
