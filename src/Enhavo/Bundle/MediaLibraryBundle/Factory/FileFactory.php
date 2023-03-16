<?php
/**
 * @author blutze-media
 * @since 2022-02-22
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Factory;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Entity\File;

class FileFactory extends \Enhavo\Bundle\MediaBundle\Factory\FileFactory
{
    /**
     * @param FileInterface $originalResource
     * @return FileInterface
     */
    public function duplicate(FileInterface $originalResource)
    {
        if ($originalResource->isLibrary()) {
            return $originalResource;
        }

        $file = parent::duplicate($originalResource);
        if ($originalResource instanceof File) {
            /** @var File $file */
            $file->setContentType($originalResource->getContentType());
        }

        return $file;
    }
}
