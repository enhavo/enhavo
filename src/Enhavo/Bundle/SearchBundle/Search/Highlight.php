<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 31.05.16
 * Time: 12:01
 */

namespace Enhavo\Bundle\SearchBundle\Search;

use Enhavo\Bundle\SearchBundle\Util\SearchUtil;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Parser;
use Enhavo\Bundle\SearchBundle\Index\Type\PdfType;

class Highlight {

    protected $util;

    protected $pieces = array();

    protected $pdfType;

    public function __construct(SearchUtil $util, PdfType $pdfType)
    {
        $this->util = $util;
        $this->pdfType = $pdfType;
    }

    public function highlight($resource, $words)
    {
        //get belonging search yml
        $currentSearchYml = $this->util->getSearchYaml($resource);
        //get fields of search yml
        $fields = $this->util->getFieldsOfSearchYml($currentSearchYml, get_class($resource));
        //go over every field and check if one or more words are in it
        $accessor = PropertyAccess::createPropertyAccessor();
        $this->pieces = array();
        foreach ($fields as $field) {
            if (property_exists($resource, $field) && $field != 'title') {
                $text = $accessor->getValue($resource, $field);
                $pieces = array();
                if (is_string($text)) {
                    $this->pieces[] = $text;
                } else if (gettype($text) == 'object') {
                    $fieldValue = $this->util->getValueOfField($field, $currentSearchYml, get_class($resource));
                    $this->getTextPieces($text, $fieldValue);
                } else if(is_array($text)){
                    foreach($text as $currentText){
                        $this->pieces[] = $currentText;
                    }
                }
            }
        }
        $pieces = $this->pieces;
        foreach ($pieces as &$piece) {
            $piece = strip_tags($piece); // remove html tags
            $lastCharacter = substr($piece, -1, 1);
            if ($lastCharacter == '.') {
                $piece = rtrim($piece, '.');
            }
        }

        $pieces = array_filter($pieces); // remove keys with value ""
        $text = implode(". ", $pieces);

        $splittedPieces = preg_split('/[.!?:;][\n ]|\n|\r|\r\n|•/', $text);
        $expression = array_shift($words);
        list($highlightedText, $countedCharacters, $splittedPieces) = $this->checkWholeExpression($splittedPieces, $expression, $words);
        list($highlightedText, $countedCharacters) = $this->highlightText($splittedPieces, $words, $countedCharacters, $highlightedText);
        $highlightedText = rtrim($highlightedText, ' · ');

        $highlightedResult = array();
        $highlightedResult['resource'] = $resource;
        $highlightedResult['highlightedText'] = $highlightedText;
        return $highlightedResult;
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
                    $newWord = $pieceWord = html_entity_decode($newWord); // html umlaut zu richrigem umlaut
                    $key = str_replace('&bdquo;', '', $key);
                    $key = str_replace('&ldquo;', '', $key);
                    $newWord = preg_replace('/\b'.preg_quote($key, '/').'\b/u', '<b class="search_highlight">' . $key . '</b>', $newWord);
                    //$newWord = htmlentities($newWord); // richtiger umlaut zu html umlaut
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

    public function getTextPieces($text, $type)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        //check what kind of indexing should happen with the text, that means check what type it has (plain, html, ...)
        if (is_array($type[0])) {
            foreach ($type[0] as $key => $value) {
                if ($key == 'Plain' || $key == 'Html') {
                    $this->pieces[] = $text;
                } else if ($key == 'Collection') {

                    //type Collection
                    //get the right yaml file for collection
                    if (array_key_exists('entity', $value)) {
                        $bundlePath = null;
                        $splittedBundlePath = explode('\\', $value['entity']);
                        while (strpos(end($splittedBundlePath), 'Bundle') != true) {
                            array_pop($splittedBundlePath);
                        }
                        $bundlePath = implode('/', $splittedBundlePath);
                        $collectionPath = null;
                        foreach ($this->util->getSearchYamls() as $path) {
                            if (strpos($path, $bundlePath)) {
                                $collectionPath = $path;
                                break;
                            }
                        }
                        $yaml = new Parser();
                        $currentCollectionSearchYamls = $yaml->parse(file_get_contents($collectionPath));
                        $collectionFields = $this->util->getFieldsOfSearchYml($currentCollectionSearchYamls, $value['entity']);
                        if ($text != null) {
                            foreach ($text as $content) {
                                foreach ($collectionFields as $field) {
                                    if (property_exists($content, $field)) {
                                        $newText = $accessor->getValue($content, $field);
                                        $type = $this->util->getValueOfField($field, $currentCollectionSearchYamls, $value['entity']);
                                        $this->getTextPieces($newText, $type);
                                    }
                                }
                            }
                        }
                    } else if (array_key_exists('type', $value)) {
                        foreach ($text as $currentText) {
                            $this->getTextPieces($currentText, $value['type']);
                        }
                    }
                } else if($key == 'PDF'){
                    //get content of PDF
                    $pdfContent = $this->pdfType->getPdfContent($text);
                    //now we can use the content as plain and add the given weight from the search.yml
                    $this->pieces[] = $pdfContent;
                }
            }
        } else {
            //Model
            $class = null;
            if ($text instanceof \Doctrine\Common\Persistence\Proxy) {
                $class = get_parent_class($text);
            } else {
                $class = get_class($text);
            }
            $currentModelSearchYaml = $this->util->getSearchYaml($text);
            $modelFields = $this->util->getFieldsOfSearchYml($currentModelSearchYaml, $class);
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach ($modelFields as $field) {
                if (property_exists($text, $field)) {
                    $newText = $accessor->getValue($text, $field);
                    $type = $this->util->getValueOfField($field, $currentModelSearchYaml, $class);
                    $this->getTextPieces($newText, $type, $field, $text);
                }
            }
        }
    }
}