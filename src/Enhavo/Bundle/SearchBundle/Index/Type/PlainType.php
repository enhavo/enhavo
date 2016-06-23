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
            if (is_numeric($word) || strlen($word) >= $minimumWordSize) {

                //check if the word is already in the list of scored words
                if (!isset($scoredWords[$word])) {
                    $scoredWords[$word] = 0;
                }

                //add score (this means if a word is already in the list of scoresWords we just add the score multiplied with the focus)
                $scoredWords[$word] += $weight * $focus;

                //the focus is getting less if a word is at the end of a long text and so the next score gets less
                $focus = min(1, .01 + 3.5 / (2 + count($scoredWords) * .015));
            }
        }
        return $scoredWords;
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