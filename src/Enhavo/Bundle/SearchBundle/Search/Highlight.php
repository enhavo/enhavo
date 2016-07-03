<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 31.05.16
 * Time: 12:01
 */

namespace Enhavo\Bundle\SearchBundle\Search;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;
use Enhavo\Bundle\SearchBundle\Index\Type\PdfType;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Enhavo\Bundle\SearchBundle\Index\IndexWalker;

class Highlight {

    protected $util;

    protected $pieces = array();

    protected $pdfType;

    protected $metadataFactory;

    protected $em;

    protected $container;

    protected $indexWalker;

    public function __construct(EntityManager $em, SearchUtil $util, PdfType $pdfType, MetadataFactory $metadataFactory, ContainerInterface $container, IndexWalker $indexWalker)
    {
        $this->em = $em;
        $this->util = $util;
        $this->pdfType = $pdfType;
        $this->metadataFactory = $metadataFactory;
        $this->container = $container;
        $this->indexWalker = $indexWalker;
    }

    public function highlight($resource, $words)
    {
        $text = $this->getRawData($resource);

        $splittedPieces = preg_split('/[.!?:;][\n ]|\n|\r|\r\n|\t|•/', $text);
        $splittedPieces = array_filter($splittedPieces);
        $expression = array_shift($words);
        list($highlightedText, $countedCharacters, $splittedPieces) = $this->checkWholeExpression($splittedPieces, $expression, $words);
        list($highlightedText, $countedCharacters) = $this->highlightText($splittedPieces, $words, $countedCharacters, $highlightedText);
        $highlightedText = rtrim($highlightedText, ' · ');

        $highlightedResult = array();
        $highlightedResult['resource'] = $resource;
        $highlightedResult['highlightedText'] = $highlightedText;
        return $highlightedResult;
    }

    protected function getRawData($resource)
    {
        if($this->container->getParameter('enhavo_search.search.search_engine') == 'enhavo_search_search_engine'){
            $resourceDataset = $this->util->getDataset($resource);
            return $resourceDataset->getRawdata();
        } else {
            $metadate = $this->metadataFactory->create($resource);
            $indexItems = $this->indexWalker->getIndexItems($resource, $metadate, array('rawData'));
            $text = '';
            foreach ($indexItems as $indexItem) {
                $text .= "\n ".$indexItem->getRawData();
            }
            return trim($text, "\n");
        }
    }

    protected function isPhrase($word)
    {
        if (str_word_count($word) > 1){
            return true;
        }
        return false;
    }

    public function checkWholeExpression($pieces, $expr, $words)
    {
        $highlightedText = null;
        $countedCharacters = 0;
        $leftPieces = array();
        $simplifiedExpr = $this->util->searchSimplify($expr);
        foreach($pieces as $piece){
            $simplifiedPiece = $this->util->searchSimplify($piece);
            if(strpos($simplifiedPiece, $simplifiedExpr) !== false){
                //contains whole search expression
                $currentPiece = array($piece);
                list($highlightedText, $countedCharacters) = $this->highlightText($currentPiece, $words, $countedCharacters, $highlightedText);
            } else {
                $leftPieces[] = $piece;
            }
        }
        return array($highlightedText, $countedCharacters, $leftPieces);
    }

    protected function highlightPhrase($piece, $searchWord, $wordsToHighlight, $countedCharacters, $words, $highlightedText)
    {
        $simplifiedPiece = $this->util->searchSimplify($piece);
        if(strpos($simplifiedPiece, $searchWord) !== false){
            $isPhrase = true;
            $splittedPieceWords = explode(" ", $piece); //not simplified words
            $splittedSearchWord = explode(" ", $searchWord); //simplified word

            foreach($splittedPieceWords as $key => $currentPieceWord){
                $currentSimplifiedPieceWord = $this->util->searchSimplify(($currentPieceWord));
                if($currentSimplifiedPieceWord == $splittedSearchWord[0]){
                    //check if next words of phrase also match
                    $counter = 1;
                    for($i = $key+1; $i < $key + count($splittedSearchWord); $i++){
                        $nextSimplifiedPieceWord = $this->util->searchSimplify(($splittedPieceWords[$i]));
                        if(!$nextSimplifiedPieceWord == $splittedSearchWord[$counter]){
                            $isPhrase = false;
                        }
                        $counter++;
                    }
                    if($isPhrase){
                        $phraseToHighlight = "";
                        for($j = $key; $j < $key + count($splittedSearchWord); $j++){
                            $phraseToHighlight .= $splittedPieceWords[$j].' ';
                        }
                        $wordsToHighlight[trim($phraseToHighlight)] = $this->util->searchSimplify($phraseToHighlight);
                    }
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
        return array($countedCharacters, $highlightedText);
    }

    protected function highlightText($pieces, $words, $countedCharacters, $highlightedText)
    {
        $pieces = array_filter($pieces);
        foreach($pieces as $piece){
            $pieceWords = explode(" ", $piece);
            foreach ($pieceWords as $key => $pieceWord) {
                $pieceWord = strip_tags($pieceWord);
                $simplifiedWord = $this->util->searchSimplify($pieceWord);
                $splittedSimplifiedWords = explode(" ", $simplifiedWord);
                foreach ($splittedSimplifiedWords as $splittedSimplifiedWord)
                {
                    $pieceWord = html_entity_decode($pieceWord);
                    $pieceWord = trim($pieceWord, ",.:;-_!?");
                    $pieceWord = htmlentities($pieceWord);
                    foreach ($words as $searchWord) {
                        if (!$this->isPhrase($searchWord)) {
                            if ($searchWord == $splittedSimplifiedWord) {
                                $wordsToHighlight[$pieceWord] = $splittedSimplifiedWord;
                            }
                        } else {
                            $isPhrase = true;
                            $splittedSearchWord = explode(" ", $searchWord);
                            if ($splittedSimplifiedWord == $splittedSearchWord[0]) {
                                //check if next words of phrase also match
                                $counter = 1;
                                for ($i = $key + 1; $i < $key + count($splittedSearchWord); $i++) {
                                    if (array_key_exists($i, $pieceWords)) {
                                        $nextSimplifiedPieceWord = $this->util->searchSimplify(($pieceWords[$i]));
                                        if ($nextSimplifiedPieceWord != $splittedSearchWord[$counter]) {
                                            $isPhrase = false;
                                        }
                                        $counter++;
                                    } else {
                                        $isPhrase = false;
                                    }

                                }
                                if ($isPhrase) {
                                    $phraseToHighlight = "";
                                    for ($j = $key; $j < $key + count($splittedSearchWord); $j++) {
                                        $phraseToHighlight .= $pieceWords[$j] . ' ';
                                    }
                                    $wordsToHighlight[trim($phraseToHighlight)] = $this->util->searchSimplify($phraseToHighlight);
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($wordsToHighlight)){
                list($countedCharacters, $newWord) = $this->countCharacters(strip_tags($piece), $words, $countedCharacters);
                foreach ($wordsToHighlight as $key => $value) {
                    $newWord = $pieceWord = html_entity_decode($newWord);
                    $key = str_replace('&bdquo;', '', $key);
                    $key = str_replace('&ldquo;', '', $key);
                    $newWord = preg_replace('/\b'.preg_quote($key, '/').'\b/u', '<b class="search_highlight">' . $key . '</b>', $newWord);
                }
                $highlightedText = $highlightedText.$newWord;
            }
        }
        return array($highlightedText, $countedCharacters);
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