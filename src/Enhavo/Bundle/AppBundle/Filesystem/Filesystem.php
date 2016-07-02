<?php

namespace Enhavo\Bundle\AppBundle\Filesystem;

/**
 * Filesystem.php
 *
 * @since 01/07/16
 * @author gseidel
 */


use Symfony\Component\Filesystem\Filesystem as SymfonyFileSystem;
use Symfony\Component\Filesystem\Exception\IOException;

class Filesystem extends SymfonyFileSystem
{
    public function readFile($path)
    {
        if($this->exists($path)) {
            return file_get_contents($path);
        }

        throw new IOException(sprintf('Failed to read file "%s", because can\'t find it', $path));
    }
}