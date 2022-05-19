<?php
/**
 * @author blutze-media
 * @since 2022-05-19
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

class FileValidationHelper
{
    public function __construct(
        private File $file,
    ) {

    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }


}
