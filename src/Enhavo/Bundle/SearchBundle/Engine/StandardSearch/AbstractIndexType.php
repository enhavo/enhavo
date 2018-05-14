<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 11:23
 */

namespace Enhavo\Bundle\SearchBundle\Index;

use Enhavo\Bundle\SearchBundle\Util\SearchUtil;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractIndexType implements IndexTypeInterface
{
    protected $util;
    protected $minimumWordSize = 2;
    protected $container;

    public function __construct(SearchUtil $util, ContainerInterface $container)
    {
        $this->util = $util;
        $this->container = $container;
    }

    public function getMinimumWordSize()
    {
        return $this->minimumWordSize;
    }

    protected function getIndexWalker()
    {
        return $this->container->get('enhavo_search_index_walker');
    }

    public function scoreWord($word, $weight, $minimumWordSize, $scoredWords, $focus)
    {
        // Check wordlength
        if (is_numeric($word) || strlen($word) >= $minimumWordSize) {
            //check if the word is already in the list of scored words
            if (!isset($scoredWords[$word])) {
                $scoredWords[$word] = 0;
            }
            //add score (this means if a word is already in the list of scoresWords we just add the score multiplied with the focus)
            $scoredWords[$word] += $weight * $focus;
            // Focus is a decaying value in terms of the amount of unique words up to this point.
            // From 100 words and more, it decays, to e.g. 0.5 at 500 words and 0.3 at 1000 words.
            $focus = min(1, .01 + 3.5 / (2 + count($scoredWords) * .015));
        }
        return array($scoredWords, $focus);
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