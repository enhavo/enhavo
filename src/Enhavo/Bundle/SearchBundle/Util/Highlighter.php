<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 31.05.16
 * Time: 12:01
 */

namespace Enhavo\Bundle\SearchBundle\Util;

use Enhavo\Bundle\SearchBundle\Extractor\Extractor;

/*
 * This class highlights a given resource
 */
class Highlighter
{
    /**
     * @var Extractor
     */
    private $extractor;

    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;
    }

    public function highlight($resource, array $words)
    {
        $text = $this->extractor->extract($resource);
        $text = implode("\n", $text);
        return $this->highlightText($text, $words);
    }

    protected function highlightText($text, array $words)
    {
        return $text;
    }
}

//class Highlight {
//
//    protected $util;
//
//    protected $pieces = array();
//
//    protected $pdfType;
//
//    protected $metadataFactory;
//
//    protected $em;
//
//    protected $container;
//
//    protected $indexWalker;
//
//    protected $textExtractor;
//
//    public function __construct(EntityManager $em, SearchUtil $util, MetadataFactory $metadataFactory, ContainerInterface $container, IndexWalker $indexWalker, TextExtractor $textExtractor)
//    {
//        $this->em = $em;
//        $this->util = $util;
//        $this->metadataFactory = $metadataFactory;
//        $this->container = $container;
//        $this->indexWalker = $indexWalker;
//        $this->textExtractor = $textExtractor;
//    }
//
//    public function highlight($resource, $words)
//    {
//        //get rawdata of resource
//        $text = $this->textExtractor->extract($resource);
//
//        //split rawdata of resource
//        $splittedPieces = preg_split('/[.!?:;][\n ]|\n|\r|\r\n|\t|•/', $text);
//        $splittedPieces = array_filter($splittedPieces);
//
//        //get the whole searchexpression
//        $expression = array_shift($words);
//
//        //check if the whole searchexpression is in the splitted raw data
//        list($highlightedText, $countedCharacters, $splittedPieces) = $this->checkWholeExpression($splittedPieces, $expression, $words);
//
//        //highlight the raw data with the splitted expression
//        list($highlightedText, $countedCharacters) = $this->highlightText($splittedPieces, $words, $countedCharacters, $highlightedText);
//        $highlightedText = rtrim($highlightedText, ' · ');
//
//        $highlightedResult = array();
//        $highlightedResult['resource'] = $resource;
//        $highlightedResult['highlightedText'] = $highlightedText;
//        return $highlightedResult;
//    }
//
//    protected function isPhrase($word)
//    {
//        //checks if the searchword is a phrase
//        if (str_word_count($word) > 1){
//            return true;
//        }
//        return false;
//    }
//
//    public function checkWholeExpression($pieces, $expr, $words)
//    {
//        //checks if a piece contains the whole search expression($expr)
//        $highlightedText = null;
//        $countedCharacters = 0;
//        $leftPieces = array();
//
//        //simplify the whole searchexpression
//        $simplifiedExpr = $this->util->searchSimplify($expr);
//
//        //walk over each piece and check if it contains the whole searchexpression
//        foreach($pieces as $piece){
//
//            //simplify the current piece
//            $simplifiedPiece = $this->util->searchSimplify($piece);
//
//            //check if the simplified piece contains the simplified whole searchexpression
//            if(strpos($simplifiedPiece, $simplifiedExpr) !== false){
//
//                //contains whole search expression --> highlight it
//                $currentPiece = array($piece);
//                list($highlightedText, $countedCharacters) = $this->highlightText($currentPiece, $words, $countedCharacters, $highlightedText);
//            } else {
//
//                //if not than put it into the array which gets highlighted later with the splitted searchexpression
//                $leftPieces[] = $piece;
//            }
//        }
//        return array($highlightedText, $countedCharacters, $leftPieces);
//    }
//
//    protected function highlightText($pieces, $words, $countedCharacters, $highlightedText)
//    {
//        //highlight the given words in the given pieces
//        $pieces = array_filter($pieces);
//        foreach($pieces as $piece){
//
//            //split the current piece into single words
//            $pieceWords = explode(" ", $piece);
//
//            //walk over every word and check if it fits to the given words to highlight
//            foreach ($pieceWords as $key => $pieceWord) {
//
//                //remove html tags
//                $pieceWord = strip_tags($pieceWord);
//
//                //simplify the current word
//                $simplifiedWord = $this->util->searchSimplify($pieceWord);
//
//                //splitt it again after simplify in case the are " " again
//                $splittedSimplifiedWords = explode(" ", $simplifiedWord);
//
//                //walk over every splitted simplified word (most time same as just simplified word)
//                foreach ($splittedSimplifiedWords as $splittedSimplifiedWord)
//                {
//                    //make html-ö to normal ö --> trim --> make normal ö to html-ö
//                    $pieceWord = html_entity_decode($pieceWord);
//                    $pieceWord = trim($pieceWord, ",.:;-_!?");
//                    $pieceWord = htmlentities($pieceWord);
//
//                    //walk over every searchword and check if it is the same as the splitted simplified word
//                    foreach ($words as $searchWord) {
//
//                        //check if the searchword is a phrase
//                        if (!$this->isPhrase($searchWord)) {
//
//                            //not a phrase
//                            if ($searchWord == $splittedSimplifiedWord) {
//
//                                //the key is the real word to replace and the value is the simplified word to search for
//                                $wordsToHighlight[$pieceWord] = $splittedSimplifiedWord;
//                            }
//                        } else {
//
//                            //it is a phrase
//                            $isPhrase = true;
//
//                            //splitt the phrase
//                            $splittedSearchWord = explode(" ", $searchWord);
//
//                            //check if the first word off the phrase matches to the current word
//                            if ($splittedSimplifiedWord == $splittedSearchWord[0]) {
//
//                                //check if next words of phrase also match and so on
//                                $counter = 1;
//                                for ($i = $key + 1; $i < $key + count($splittedSearchWord); $i++) {
//                                    if (array_key_exists($i, $pieceWords)) {
//                                        $nextSimplifiedPieceWord = $this->util->searchSimplify(($pieceWords[$i]));
//                                        if ($nextSimplifiedPieceWord != $splittedSearchWord[$counter]) {
//                                            $isPhrase = false;
//                                        }
//                                        $counter++;
//                                    } else {
//                                        $isPhrase = false;
//                                    }
//                                }
//
//                                //if isPhrase is still true, we have found the phrase --> highlight the phrase
//                                if ($isPhrase) {
//                                    $phraseToHighlight = "";
//                                    for ($j = $key; $j < $key + count($splittedSearchWord); $j++) {
//                                        $phraseToHighlight .= $pieceWords[$j] . ' ';
//                                    }
//                                    $wordsToHighlight[trim($phraseToHighlight)] = $this->util->searchSimplify($phraseToHighlight);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//
//            //if there are words to highlight, highlight them
//            if(!empty($wordsToHighlight)){
//
//                //check if there is enough space left for the piece
//                //the function returns the sentence ($newWord) witch to show and the amount of characters
//                list($countedCharacters, $newWord) = $this->countCharacters(strip_tags($piece), $words, $countedCharacters);
//                foreach ($wordsToHighlight as $key => $value) {
//
//                    //make html-ö to normal ö --> trim --> make normal ö to html-ö
//                    $newWord = $pieceWord = html_entity_decode($newWord);
//                    $key = str_replace('&bdquo;', '', $key);
//                    $key = str_replace('&ldquo;', '', $key);
//
//                    //replace
//                    $newWord = preg_replace('/\b'.preg_quote($key, '/').'\b/u', '<b class="search_highlight">' . $key . '</b>', $newWord);
//                }
//
//                //add to highlighted text
//                $highlightedText = $highlightedText.$newWord;
//            }
//        }
//
//        //return highlighted text and counted characters
//        return array($highlightedText, $countedCharacters);
//    }
//
//    protected function countCharacters($sentence, $words, $charactersLength)
//    {
//        $collectedSentencesWithSearchword = "";
//
//        //check if the current sentence has more than 20 words
//        if(str_word_count($sentence) <= 20 && $sentence != ""){
//            list($charactersLength, $collectedSentencesWithSearchword) = $this->addSentenceIfPossible($sentence, $charactersLength, $words, $collectedSentencesWithSearchword);
//        } else {
//
//            //yes there are more than 20 words
//            //divide sentence into two pieces
//            list($firstPart, $secondPart) = $this->getDividedSentence($sentence);
//
//            //try to add the first part of the sentence
//            $beforeAddingFirstPart = $collectedSentencesWithSearchword;
//            list($charactersLength, $collectedSentencesWithSearchword) = $this->addSentenceIfPossible($firstPart, $charactersLength, $words, $collectedSentencesWithSearchword, true, true);
//
//            // check if before adding the first part is same as after adding.
//            // This means the word was not found in the first part.
//            // So we have to add '...' befor the second part in case the word is in the second part
//            if($collectedSentencesWithSearchword == $beforeAddingFirstPart){
//                $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.' ... ';
//            }
//
//            //try to add the second part of the sentence
//            $beforeAddingSecondPart = $collectedSentencesWithSearchword;
//            list($charactersLength, $collectedSentencesWithSearchword) = $this->addSentenceIfPossible($secondPart, $charactersLength, $words, $collectedSentencesWithSearchword, false, true);
//
//            // check if before adding the second part is same as after adding.
//            // This means the word was not found in the second part.
//            if($collectedSentencesWithSearchword == $beforeAddingSecondPart){
//
//                //check if we added '...' before. If we did, remove it
//                if($collectedSentencesWithSearchword == ' ... '){
//
//                    //remove
//                    $collectedSentencesWithSearchword = rtrim($collectedSentencesWithSearchword, ' ... ');
//                } else {
//
//                    //the word was found in the first part but not in the second. So add '...' after the first part
//                    $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.' ... · ';
//                }
//            }
//        }
//
//        //return counted characters an the parts of the sentences with the word
//        return array($charactersLength, $collectedSentencesWithSearchword);
//    }
//
//    protected function addSentenceIfPossible($sentence, $charactersLength, $words, $collectedSentencesWithSearchword, $newSentence = true, $devidedSentence = false)
//    {
//        //no there are less than 20 words --> everything is fine
//        $simplifiedSentence = $this->util->searchSimplify($sentence);
//        $length = strlen($simplifiedSentence); //lenght of the current sentence
//        if($charactersLength + $length <= 160){ //check if there is still enough place to add the current sentence
//
//            //sentence can be added
//            //Check if a searchword is in the current sentence
//            $wordIn = $this->wordInSentence($simplifiedSentence, $words);
//            // if there is at least one searchword in the current sentence then add the sentence
//            if($wordIn){
//                //check if the sentence is new. This means if it is just the second part of a sentence we do not need the '. ·'
//                if($newSentence){
//                    if(!$devidedSentence){
//                        $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.$sentence.'. · ';
//                    } else {
//                        $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.$sentence;
//                    }
//                } else {
//
//                    //add the sentence
//                    $sentence = ' '.$sentence;
//                    $collectedSentencesWithSearchword = $collectedSentencesWithSearchword.$sentence.'. · ';
//                }
//
//                // add length of the sentence to character length
//                $charactersLength += $length;
//            }
//        }
//
//        //return new character length and new sentence to highlight
//        return array($charactersLength, $collectedSentencesWithSearchword);
//    }
//
//    protected function getDividedSentence($sentence)
//    {
//        //deviedes a sentence into two parts
//        $pieces = explode(" ", $sentence);
//        $pieces = array_filter($pieces); // remove keys with value ""
//        $pieces = array_values($pieces);
//        $countWords = count($pieces);
//
//        $firstPart = $pieces;
//        $firstPart = implode(" ", array_splice($firstPart, 0, $countWords / 2));
//
//        $otherPart = $pieces;
//        $otherPart = implode(" ", array_splice($otherPart, $countWords / 2));
//        return array($firstPart, $otherPart);
//    }
//
//    protected function wordInSentence($sentence, $words)
//    {
//        foreach ($words as $word) {
//            if (preg_match("/\b" . $word . "\b/i", $sentence)) {
//
//                //yes there is at least one searchword in the sentence --> add sentence
//                return true;
//            }
//        }
//        return false;
//    }
//}