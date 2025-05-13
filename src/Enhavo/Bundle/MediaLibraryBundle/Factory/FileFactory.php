<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Factory;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Model\LibraryFileInterface;

class FileFactory extends \Enhavo\Bundle\MediaBundle\Factory\FileFactory
{
    public function createFromFile(FileInterface $file): FileInterface
    {
        $newFile = parent::createFromFile($file);
        if ($newFile instanceof LibraryFileInterface && $file instanceof LibraryFileInterface) {
            $newFile->setItem($file->getItem());
        }

        return $newFile;
    }
}
