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
    function index($value, $options, $properties = null)
    {
        //create a new IndexItem
        $indexItem = new IndexItem();

        //if rawData is set in properties or properties is empty -> set rawData in the IndexItem
        if(empty($properties) || in_array('rawData', $properties)){
            $indexItem->setRawData($value);
        }

        $weight = 1;
        if (isset($options['weight'])) {
            $weight = $options['weight'];
        }

        //if weight is set in properties or properties is empty -> set weight in the IndexItem
        if(empty($properties) || in_array('weight', $properties)){
            $indexItem->setWeight($weight);
        }

        //if data or scoredWords is set in properties or properties is empty -> set data and scoredWords in the IndexItem
        if(empty($properties) || in_array('data', $properties) || in_array('scoredWords', $properties)) {
            $accum = ' ';

            $minimumWordSize = $this->getMinimumWordSize();

            //indexing plain text and save in DB
            //get seperated words
            $words = $this->searchIndexSplit($value);
            $scoredWords = array();

            //set focus to 1 at the beginning
            $focus = 1;

            //get the right score for every word
            foreach ($words as $word) {
                // Add word to accumulator
                $accum .= $word . " ";
                list($scoredWords, $focus) = $this->scoreWord($word, $weight, $minimumWordSize, $scoredWords, $focus);
            }

            //prepare the scoredWordsArray for IndexItem
            $indexItemArray = [];
            $counter = 0;
            foreach ($scoredWords as $word => $score) {
                $indexItemArray[$counter]['word'] = $word;
                $indexItemArray[$counter]['locale'] = $this->container->getParameter('locale');
                $indexItemArray[$counter]['type'] = $options['type'];
                $indexItemArray[$counter]['score'] = $score;
                $counter++;
            }
            $indexItem->setData(rtrim($accum));
            $indexItem->setScoredWords($indexItemArray);
        }
        return array($indexItem);
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