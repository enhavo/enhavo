<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Indexer\Indexer;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Indexer\Index;
use Enhavo\Bundle\SearchBundle\Indexer\IndexerInterface;

class HtmlIndexer extends AbstractType implements IndexerInterface
{
    function getIndexes($value, array $options = [])
    {
        $data = [];

        $tags = $this->getHtmlTags();

        // strip off all ignored tags, insert space before and after them to keep word boundaries.
        $value = str_replace(array('<', '>'), array(' <', '> '), $value);
        $value = strip_tags($value, '<' . implode('><', $tags) . '>');

        // split html tags from plain text.
        $split = preg_split('/\s*<([^>]+?)>\s*/', $value, -1, PREG_SPLIT_DELIM_CAPTURE);

        $tag = false; // odd/even counter. Tag or no tag.
        $tagName = null;
        $tagStack = array();
        foreach ($split as $tagValue) {
            // if tag is true we are handling the tags in the array, if tag is false we are handling text between the tags
            if ($tag) {
                list($tagName) = explode(' ', $tagValue, 2);
                $tagName = strtolower($tagName);

                // Closing or opening tag?
                if ($tagName[0] == '/') {
                    array_pop($tagStack);
                } else {
                    $tagStack[] = $tagName;
                }
            } else {
                if ($tagValue != '') {
                    $index = new Index();
                    $currentTag = null;
                    if(count($tagStack)) {
                        $currentTag = $tagStack[count($tagStack) - 1];
                    }
                    $index->setWeight($this->getWeight($currentTag));
                    $index->setValue(trim($tagValue));
                    $data[] = $index;
                }
            }
            $tag = !$tag;
        }

        return $data;
    }

    private function getWeight($tag, array $weights = null)
    {
        $map = $this->getHtmlMap();

        if(is_array($weights)) {
            $map = array_merge($map, $weights);
        }

        if(array_key_exists($tag, $map)) {
            return $map[$tag];
        }

        return 1;
    }

    private function getHtmlTags(array $weights = null)
    {
        $map = $this->getHtmlMap();

        if(is_array($weights)) {
            $map = array_merge($map, $weights);
        }

        return array_keys($map);
    }

    private function getHtmlMap()
    {
        $map =  [
            'h1' => 25,
            'h2' => 18,
            'h3' => 15,
            'h4' => 14,
            'h5' => 9,
            'h6' => 6,
            'u' => 3,
            'b' => 3,
            'i' => 3,
            'strong' => 3,
            'em' => 3,
            'a' => 10,
            'p' => 1
        ];

        return $map;
    }

    public function getType()
    {
        return 'html';
    }

}