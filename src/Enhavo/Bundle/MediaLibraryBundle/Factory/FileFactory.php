<?php

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
