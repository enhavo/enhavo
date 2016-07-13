<?php

namespace Enhavo\Bundle\SearchBundle\Search;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

/*
 * This class does the search process for enhavo
 */
class SearchRequest {

    /**
     * Entered search keywords.
     *
     * @var string
     */
    protected $searchExpression;

    /**
     * Array of search words.
     *
     * @var array
     */
    protected $words = array();

    /**
     * Limit of AND and OR.
     *
     * @var integer
     */
    protected $andOrLimit = 8;

    /**
     * Indicates whether the query conditions are simple or complex (LIKE,OR,AND).
     *
     * @var bool
     */
    protected $simple = true;

    /**
     * Parsed-out positive and negative search keys.
     *
     * @var array
     */
    protected $keys = array('positive' => array(), 'negative' => array());

    /**
     * Conditions (AND, OR, ..) which are used in the search expression.
     */
    protected $conditions;

    /**
     * Indicates how many matches for a search query are necessary.
     *
     * @var int
     */
    protected $matches = 0;

    /**
     * Only words with more than 3 letters get indexed.
     *
     * @var int
     */
    protected $minimumWordSize = 3;

    /**
     * Is true if there are to many AND/OR expressions
     *
     * @var bool
     */
    protected $toManyExpressions = false;

    protected $normalizeResults = false;

    protected $container;

    protected $em;

    protected $util;
    protected $phrase = false;
    protected $phrases = array();
    protected $wordsWithoutPhrases = array();

    public function __construct(Container $container, EntityManager $em, SearchUtil $util)
    {
        $this->container = $container;
        $this->em = $em;
        $this->util = $util;
    }

    public function search($types, $fields)
    {
        //get the search results (language, type, id, calculated_score)
        $results = $this->em
            ->getRepository('EnhavoSearchBundle:Index')
            ->getSearchResults($this->conditions, $this->matches, $this->simple, $types, $fields);

        //prepare data with found results -> get Index from id -> get Dataset from Index
        $data = array();
        foreach ($results as $resultIndex) {
            $currentIndex = $this->em
                ->getRepository('EnhavoSearchBundle:Index')
                ->findOneBy(array('id' => $resultIndex['id']));
            $currentDataset = $currentIndex->getDataset();
            $dataForSearchResult = array();
            $dataForSearchResult['type'] = $currentDataset->getType();
            $dataForSearchResult['bundle'] = $currentDataset->getBundle();
            $dataForSearchResult['reference'] = $currentDataset->getReference();
            $data[] = $dataForSearchResult;
        }

        $finalResults = array();
        foreach ($data as $resultData) {

            //get Element from the entity
            $currentObject = $this->em
                ->getRepository($resultData['bundle'].':'.ucfirst($resultData['type']))
                ->findOneBy(array('id' => $resultData['reference']));
            $finalResults[] = $currentObject;
        }

        return $finalResults;
    }

    /**
     * Parses the search query into SQL conditions.
     * The functionality is similar to drupal
     * Sets up the following variables:
     * - $this->keys
     * - $this->words
     * - $this->conditions
     * - $this->simple (it is not simple if the expression contains or, not or a phrase)
     * - $this->matches
     */
    public function parseSearchExpression($searchExpression) {

        $this->searchExpression = $searchExpression;

        //separates the searchExpression: each word becomes an array -> the first value is the original word with a space added, the second is the optional - sign, and the third is the word without the extra space
        //if it is a quoted string it takes all in between the quotes as "one word"
        preg_match_all('/ (-?)("[^"]+"|[^" ]+)/i', ' ' .  $this->searchExpression , $keywords, PREG_SET_ORDER);

        if (count($keywords) ==  0) {
            return;
        }

        // Classify tokens.
        $inOR = false;
        $limitCombinations = $this->andOrLimit;
        //and_count is set to -1 because in the loop of the first word die and_count gets increased (and there was no AND yet) and in the lop of the second word the and_count gets increased also.
        //So the count after two words would be 2 even if there was just 1 AND --> so we start with -1
        $andCount = -1;
        $orCount = 0;

        //Sort keywords in positive and negative
        foreach ($keywords as $match) {

            //check if there are not to many AND/OR expressions
            if ($orCount && $andCount + $orCount >= $limitCombinations) {
                //To many AND/OR expressions
                $this->toManyExpressions = true;
                break;
            }

            //Checking for quotes
            $phrase = false;
            if ($match[2]{0} == '"') {
                $match[2] = substr($match[2], 1, -1);
                $phrase = true;
                $this->simple = false;
                $this->phrase = true;
                $currentPhrase = $this->util->searchSimplify($match[2]);
                $this->phrases[] = trim($currentPhrase, '"');
            }

            //Symplify match
            $words = $this->util->searchSimplify($match[2]);

            // Re-explode in case simplification added more words, except when
            // matching a phrase.
            if($phrase){
                $words = array($words);
            } else {
                $words = preg_split('/ /', $words, -1, PREG_SPLIT_NO_EMPTY);
            }

            // NOT
            if ($match[1] == '-') {
                $this->keys['negative'] = array_merge($this->keys['negative'], $words);
            }
            // OR
            elseif ($match[2] == 'OR' && count($this->keys['positive'])) {
                $last = array_pop($this->keys['positive']);
                // Starting a new OR?
                if (!is_array($last)) {
                    $last = array($last);
                }
                $this->keys['positive'][] = $last;
                $inOR = true;
                $orCount++;
                continue;
            }
            // AND operator: implied, so just ignore it.
            elseif ($match[2] == 'AND' || $match[2] == 'and') {
                continue;
            } else { // Plain keyword.
                if ($inOR) {
                    // Add to last element (which is an array).
                    $this->keys['positive'][count($this->keys['positive']) - 1] = array_merge($this->keys['positive'][count($this->keys['positive']) - 1], $words);
                }
                else {
                    $this->keys['positive'] = array_merge($this->keys['positive'], $words);
                    $andCount++;
                }
            }
            $inOR = false;
        }

        $hasAnd = false;
        $hasOr = false;

        //Prepare AND/OR conditions (Positive matches)
        foreach ($this->keys['positive'] as $key) {

            // Group of ORed terms.
            if (is_array($key) && count($key)) {
                // If we had already found one OR, this is another one AND-ed with the
                // first, meaning it is not a simple query.
                if ($hasOr) {
                    $this->simple = false;
                }
                $hasOr = true;
                $hasNewScores = false;
                $queryOr = array();
                foreach ($key as $or) {
                    if(!in_array($or, $this->phrases)){
                        $this->wordsWithoutPhrases[] = $or;
                    }
                    list($numNewScores) = $this->parseWord($or);
                    $hasNewScores |= $numNewScores;
                    $queryOr[] = $or;
                }
                if (count($queryOr)) {
                    $this->conditions['OR'][] = $queryOr;
                    // A group of OR keywords only needs to match once.
                    $this->matches += ($hasNewScores > 0);
                }
            } else { // Single ANDed term.
                $hasAnd = true;
                if(!in_array($key, $this->phrases)){
                    $this->wordsWithoutPhrases[] = $key;
                }
                list($numNewScores, $numValidWords) = $this->parseWord($key);
                $this->conditions['AND'][] = $key;
                if (!$numValidWords) {
                    $this->simple = false;
                }
                // Each AND keyword needs to match at least once.
                $this->matches += $numNewScores;
            }
        }
        if ($hasAnd && $hasOr) {
            $this->simple = false;
        }

        // Negative matches.
        foreach ($this->keys['negative'] as $key) {
            $this->conditions['NOT'][] = $key;
            $this->simple = false;
        }
    }

    /**
     * Sets the entered words to $this->words, if it is not already in there.
     * Increases the 'new-scores' variable if there was addad a new word to §this->words and checks if the word is valid, that means if the minimum wordsize is reached.
     * Splits also phrase entires into words.
     * num_new_scores is important for the matches in SQL later.
     * This is the same as in drupal
     */
    protected function parseWord($word) {
        $numNewScores = 0;
        $numValidWords = 0;

        // Determine the scorewords of this word/phrase.
        $split = explode(' ', $word);
        foreach ($split as $s) {
            $num = is_numeric($s);
            if ($num || strlen($s) >= $this->minimumWordSize) {
                if (!isset($this->words[$s])) {
                    $this->words[$s] = $s;
                    $numNewScores++;
                }
                $numValidWords++;
            }
        }

        // Return matching snippet and number of added words.
        return array($numNewScores, $numValidWords);
    }

    public function hasToManyExpressions()
    {
        return $this->toManyExpressions;
    }


    public function getWords()
    {
        $wordsResultArray = [];

        //the first position of the array is the whole searchexpression
        $wordsResultArray[] = $this->searchExpression;

        //check if there is a phrase in the searchexpression
        if($this->phrase == true){
            $wordsResultArray = array_merge($wordsResultArray, $this->phrases, $this->wordsWithoutPhrases);
            return $wordsResultArray;
        }
        $wordsResultArray = array_merge($wordsResultArray, $this->words);
        return $wordsResultArray;
    }
}