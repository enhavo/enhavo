<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Enhavo\Bundle\SearchBundle\Index\AbstractIndexType;
use Enhavo\Bundle\SearchBundle\Index\IndexItem;

class PlainType extends AbstractIndexType
{
    function index($value, $options)
    {
        $accum = ' ';
        $weight = 1;
        if(isset($options['weight'])) {
            $weight = $options['weight'];
        }

        $minimumWordSize = $this->getMinimumWordSize();

        //indexing plain text and save in DB
        //get seperated words
        $words = $this->searchIndexSplit($value);
        $scoredWords = array();

        //set focus to 1 at the beginning
        $focus = 1;

        //get the right score for every word
        foreach($words as $word) {
            // Add word to accumulator
            $accum .= $word." ";
            list($scoredWords, $focus) = $this->scoreWord($word, $weight, $minimumWordSize, $scoredWords, $focus);
        }

        $indexItemArray = [];
        $counter = 0;
        foreach ($scoredWords as $word => $score) {
            $indexItemArray[$counter]['word'] = $word;
            $indexItemArray[$counter]['locale'] = $this->container->getParameter('locale');
            $indexItemArray[$counter]['type'] = $options['type'];
            $indexItemArray[$counter]['score'] = $score;
            $counter++;
        }

        $indexItem = new IndexItem();
        $indexItem->setRawData($value);
        $indexItem->setData(rtrim($accum));
        $indexItem->setScoredWords($indexItemArray);
        return $indexItem;
    }

    /**
     * Returns a unique type name for this type
     *
     * @return string
     */
    public function getType()
    {
        return 'Plain';
    }

}