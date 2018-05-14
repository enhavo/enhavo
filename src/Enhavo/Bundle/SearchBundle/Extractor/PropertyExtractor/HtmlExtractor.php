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

class HtmlExtractor extends AbstractType implements PropertyExtractorInterface
{
    public function extract($value, $options = [])
    {
        if($value) {
            $text = strip_tags($value);
            $text = preg_split('/\s+/', $text);
            $text = implode(' ', $text);
            $text = trim($text);
            return [$text];
        }
        return [];
    }

    public function getType()
    {
        return 'html';
    }
}