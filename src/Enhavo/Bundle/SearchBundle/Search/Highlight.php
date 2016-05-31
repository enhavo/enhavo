<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 31.05.16
 * Time: 12:01
 */

namespace Enhavo\Bundle\SearchBundle\Search;

use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

class Highlight {

    protected $util;

    public function __construct(SearchUtil $util)
    {
        $this->util = $util;
    }

    public function highlight($text, $words)
    {
        $highlightedText = null;
        $countedCharacters = 0;
        $pieces = explode('. ', $text);

        foreach($pieces as $piece){
            $pieceWords = explode(" ", $piece);
            $wordsToHighlight = array();
            foreach ($pieceWords as $pieceWord) {
                $simplifiedWord = $this->util->searchSimplify($pieceWord);
                foreach ($words as $searchWord) {
                    if ($searchWord == $simplifiedWord) {
                        $wordsToHighlight[$pieceWord] = $simplifiedWord;
                    }
                }
            }
            if(!empty($wordsToHighlight)){
                list($countedCharacters, $newWord) = $this->countCharacters(strip_tags($piece), $words, $countedCharacters);
                foreach ($wordsToHighlight as $key => $value) {
                    $newWord = preg_replace('/\b'.$key.'\b/u', '<b class="search_highlight">' . $key . '</b>', $newWord);
                }
                $highlightedText = $highlightedText.$newWord;
            }
        }

        return rtrim($highlightedText, ' · ');
    }

    protected function countCharacters($sentence, $words, $charactersLength)
    {
        $collectedSentencesWithSearchword = "";
        //check if the current sentence has more than 20 words
        if(str_word_count($sentence) <= 20 && $sentence != ""){
            list($charactersLength, $collectedSentencesWithSearchword) = $this->addSentenceIfPossible($sentence, $charactersLength, $words, $collectedSentencesWithSearchword);
        } else {
            //yes there are more than 20 words
            //check if half of the current sentence is still to long
            list($firstPart, $secondPart) = $this->getDividedSentence($sentence);

            $beforeAddingFirstPart = $collectedSentencesWithSearchword;
            list($charactersLength, $collectedSentencesWithSearchword) = $this->addSentenceIfPossible($firstPart, $charactersLength, $words, $collectedSentencesWithSearchword, true, true);
            if($collectedSentencesWithSearchword == $beforeAddingFirstPart){
                $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.' ... ';
            }

            $beforeAddingSecondPart = $collectedSentencesWithSearchword;
            list($charactersLength, $collectedSentencesWithSearchword) = $this->addSentenceIfPossible($secondPart, $charactersLength, $words, $collectedSentencesWithSearchword, false, true);
            if($collectedSentencesWithSearchword == $beforeAddingSecondPart){
                if($collectedSentencesWithSearchword == ' ... '){
                    $collectedSentencesWithSearchword = rtrim($collectedSentencesWithSearchword, ' ... ');
                } else {
                    $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.' ... · ';
                }
            }
        }
        return array($charactersLength, $collectedSentencesWithSearchword);
    }

    protected function addSentenceIfPossible($sentence, $charactersLength, $words, $collectedSentencesWithSearchword, $newSentence = true, $devidedSentence = false)
    {
        //no there are less than 20 words --> everything is fine
        $simplifiedSentence = $this->util->searchSimplify($sentence);
        $length = strlen($simplifiedSentence); //lenght of the current sentence
        if($charactersLength + $length <= 160){ //check if there is still enough place to add the current sentence
            //sentence can be added
            //Check if a searchword is in the current sentence
            $wordIn = $this->wordInSentence($simplifiedSentence, $words);
            // if there is at least one searchword in the current sentence then add the sentence
            if($wordIn){
                if($newSentence){
                    if(!$devidedSentence){
                        $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.$sentence.'. · ';
                    } else {
                        $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.$sentence;

                    }
                } else {
                    $sentence = ' '.$sentence;
                    $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.$sentence.'. · ';
                }
                $charactersLength += $length;
            }
        }
        return array($charactersLength, $collectedSentencesWithSearchword);
    }

    protected function getDividedSentence($sentence)
    {
        $pieces = explode(" ", $sentence);
        $pieces = array_filter($pieces); // remove keys with value ""
        $pieces = array_values($pieces);
        $countWords = count($pieces);

        $firstPart = $pieces;
        $firstPart = implode(" ", array_splice($firstPart, 0, $countWords / 2));

        $otherPart = $pieces;
        $otherPart = implode(" ", array_splice($otherPart, $countWords / 2));
        return array($firstPart, $otherPart);
    }

    protected function wordInSentence($sentence, $words)
    {
        foreach ($words as $word) {
            if (preg_match("/\b" . $word . "\b/i", $sentence)) {
                //yes there is at least one searchword in the sentence --> add sentence
                return true;
            }
        }
        return false;
    }
}