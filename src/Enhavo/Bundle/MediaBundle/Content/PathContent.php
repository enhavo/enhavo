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

use Enhavo\Bundle\MediaBundle\Exception\FileException;

class PathContent extends AbstractContent
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
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
