<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Extractor\PropertyExtractor;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Extractor\PropertyExtractorInterface;

class TextExtractor extends AbstractType implements PropertyExtractorInterface
{
    function extract($value, $options = [])
    {
        if($value) {
            return [trim($value)];
        }
        return [];
    }

    public function getType()
    {
        return 'text';
    }
}