<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Filter;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Exception\FilterException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

abstract class AbstractFilter extends AbstractType implements FilterInterface
{
    /**
     * @throws FilterException
     *
     * @return ContentInterface
     */
    protected function getContent($file)
    {
        if ($file instanceof FileInterface) {
            return $file->getContent();
        } elseif ($file instanceof FormatInterface) {
            return $file->getContent();
        } elseif ($file instanceof ContentInterface) {
            return $file;
        }

        throw new FilterException(sprintf('Unsupported type "%s" for media filter', get_class($file)));
    }

    protected function setMimeType($file, $mimeType)
    {
        if ($file instanceof FileInterface) {
            $file->setMimeType($mimeType);
        } elseif ($file instanceof FormatInterface) {
            $file->setMimeType($mimeType);
        }
    }

    protected function setExtension($file, $extension)
    {
        if ($file instanceof FileInterface) {
            $file->setExtension($extension);
        } elseif ($file instanceof FormatInterface) {
            $file->setExtension($extension);
        }
    }

    protected function getImageFormat(ContentInterface $content)
    {
        $type = exif_imagetype($content->getFilePath());
        switch ($type) {
            case IMAGETYPE_GIF:
                return 'gif';
            case IMAGETYPE_JPEG:
                return 'jpg';
            case IMAGETYPE_PNG:
                return 'png';
            case IMAGETYPE_BMP:
                return 'bmp';
            case IMAGETYPE_WEBP:
                return 'webp';
        }

        return null;
    }

    protected function getMimeTypeFromImageFormat($imageFormat)
    {
        switch ($imageFormat) {
            case 'gif':
                return 'image/gif';
            case 'jpg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'bmp':
                return 'image/bmp';
            case 'webp':
                return 'image/webp';
        }

        return null;
    }
}
