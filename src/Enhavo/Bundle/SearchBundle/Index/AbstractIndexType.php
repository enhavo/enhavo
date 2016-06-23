<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 11:23
 */

namespace Enhavo\Bundle\SearchBundle\Index;

use Enhavo\Bundle\SearchBundle\Index\IndexEngine;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

abstract class AbstractIndexType implements IndexTypeInterface
{
    protected $util;
    protected $indexEngine;

    public function __construct(SearchUtil $util, IndexEngine $indexEngine)
    {
        $this->util = $util;
        $this->indexEngine = $indexEngine;
    }

    public function getMinimumWordSize($options)
    {
        $minimumWordSize = 2;
        if(isset($options['minimumWordSize'])) {
            $minimumWordSize = $options['minimumWordSize'];
        }
        return $minimumWordSize;
    }


    /**
     * Simplifies and splits a string into words for indexing
     */
    public function searchIndexSplit($text) {
        $text = $this->util->searchSimplify($text);
        $words = explode(' ', $text);
        return $words;
    }
}