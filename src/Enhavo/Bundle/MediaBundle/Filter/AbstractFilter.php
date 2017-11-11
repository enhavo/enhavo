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
    protected function getContent($file)
    {
        if($file instanceof  FileInterface) {
            return $file->getContent();
        }

        if($file instanceof FormatInterface) {
            return $file->getContent();
        }

        if($file instanceof ContentInterface) {
            return $file->getContent();
        }

        throw new FilterException(sprintf('Unsupported type "%s" for media filter', get_class($file)));
    }
}