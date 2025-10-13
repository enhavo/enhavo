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

    public function __construct(?string $content = null, ?string $path = null)
    {
        if ($path === null) {
            $path = tempnam(sys_get_temp_dir(), 'Content');
        }

        file_put_contents($path, $content ?? '');
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
