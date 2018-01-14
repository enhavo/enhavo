<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.11.17
 * Time: 13:48
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
     * @param $file
     * @return ContentInterface
     * @throws FilterException
     */
    protected function getContent($file)
    {
        if($file instanceof FileInterface) {
            return $file->getContent();
        } elseif($file instanceof FormatInterface) {
            return $file->getContent();
        } elseif($file instanceof ContentInterface) {
            return $file;
        }

        throw new FilterException(sprintf('Unsupported type "%s" for media filter', get_class($file)));
    }

    protected function setMimeType($file, $mimeType)
    {
        if($file instanceof FileInterface) {
            $file->setMimeType($mimeType);
        } elseif($file instanceof FormatInterface) {
            $file->setMimeType($mimeType);
        }
    }

    protected function setExtension($file, $extension)
    {
        if($file instanceof FileInterface) {
            $file->setExtension($extension);
        } elseif($file instanceof FormatInterface) {
            $file->setExtension($extension);
        }
    }
}