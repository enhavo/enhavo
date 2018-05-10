<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 14:31
 */

namespace Enhavo\Bundle\SearchBundle\Metadata;


use Enhavo\Bundle\AppBundle\Metadata\ParserInterface;

class FilterParser implements ParserInterface
{
    public function parse(array &$metadataArray, $configuration)
    {
        if(isset($configuration['filter']) && is_array($configuration['filter'])) {
            $data = $configuration['filter'];
            foreach($data as $name => $value) {
                $metadataArray['filter'][$name] = $value;
            }
        }
    }
}