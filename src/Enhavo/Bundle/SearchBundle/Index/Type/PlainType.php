<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Enhavo\Bundle\SearchBundle\Index\AbstractIndexType;

class PlainType extends AbstractIndexType
{
    function index($value, $options)
    {
        $accum = $options['accum'];
        $weight = 1;
        if(isset($options['weight'])) {
            $weight = $options['weight'];
        }

        $minimumWordSize = $this->getMinimumWordSize($options);

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
        return array($scoredWords, $accum);
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