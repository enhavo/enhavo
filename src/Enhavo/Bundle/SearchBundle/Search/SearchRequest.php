<?php

namespace Enhavo\Bundle\SearchBundle\Search;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

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
    protected $and_or_limit = 8;

    /**
     * Indicates whether the query conditions are simple or complex (LIKE,OR,AND).
     *
     * @var bool
     */
    protected $simple = TRUE;

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
    protected $minimum_word_size = 3;

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

    public function __construct(Container $container, EntityManager $em, SearchUtil $util)
    {
        $this->container = $container;
        $this->em = $em;
        $this->util = $util;
    }

    public function search()
    {
        //get the search results (language, type, id, calculated_score)
        $results = $this->em
            ->getRepository('EnhavoSearchBundle:Index')
            ->getSearchResults($this->conditions, $this->matches, $this->simple);

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
            $currentData = array();
            $currentObject = $this->em
                ->getRepository($resultData['bundle'].':'.ucfirst($resultData['type']))
                ->findOneBy(array('id' => $resultData['reference']));

            $currentObject = $this->highlightText($currentObject);

            $currentData['entityName'] = strtolower($resultData['type']);
            $formatedBundleName = str_replace('Bundle', '', $resultData['bundle']); //EnhavoArticle
            $splittedBundleName = preg_split('/(?=[A-Z])/', $formatedBundleName, -1, PREG_SPLIT_NO_EMPTY); // Enhavo Article
            $formatedBundleName = implode('_', $splittedBundleName); //Enhavo_Article
            $currentData['bundleName'] = strtolower($formatedBundleName); //enhavo_article
            $currentData['object'] = $currentObject;
            $finalResults[] = $currentData;
        }

        return $finalResults;
    }

    /**
     * Parses the search query into SQL conditions.
     *
     * Sets up the following variables:
     * - $this->keys
     * - $this->words
     * - $this->conditions
     * - $this->simple
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
        $in_or = FALSE;
        $limit_combinations = $this->and_or_limit;
        //and_count is set to -1 because in the loop of the first word die and_count gets increased (and there was no AND yet) and in the lop of the second word the and_count gets increased also.
        //So the count after two words would be 2 even if there was just 1 AND --> so we start with -1
        $and_count = -1;
        $or_count = 0;

        //Sort keywords in positive and negative
        foreach ($keywords as $match) {

            //check if there are not to many AND/OR expressions
            if ($or_count && $and_count + $or_count >= $limit_combinations) {
                //To many AND/OR expressions
                $this->toManyExpressions = true;
                break;
            }

            //Checking for quotes
            $phrase = FALSE;
            if ($match[2]{0} == '"') {
                $match[2] = substr($match[2], 1, -1);
                $phrase = TRUE;
                $this->simple = FALSE;
            }

            //Symplify match
            $words = $this->util->searchSimplify($match[2]);
            // Re-explode in case simplification added more words, except when
            // matching a phrase.
            $words = $phrase ? array($words) : preg_split('/ /', $words, -1, PREG_SPLIT_NO_EMPTY);//!!!

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
                $in_or = TRUE;
                $or_count++;
                continue;
            }
            // AND operator: implied, so just ignore it.
            elseif ($match[2] == 'AND' || $match[2] == 'and') {
                continue;
            }

            // Plain keyword.
            else {
                if ($in_or) {
                    // Add to last element (which is an array).
                    $this->keys['positive'][count($this->keys['positive']) - 1] = array_merge($this->keys['positive'][count($this->keys['positive']) - 1], $words);
                }
                else {
                    $this->keys['positive'] = array_merge($this->keys['positive'], $words);
                    $and_count++;
                }
            }
            $in_or = FALSE;
        }


        $has_and = FALSE;
        $has_or = FALSE;
        //Prepare AND/OR conditions (Positive matches)
        foreach ($this->keys['positive'] as $key) {

            // Group of ORed terms.
            if (is_array($key) && count($key)) {
                // If we had already found one OR, this is another one AND-ed with the
                // first, meaning it is not a simple query.
                if ($has_or) {
                    $this->simple = FALSE;
                }
                $has_or = TRUE;
                $has_new_scores = FALSE;
                $queryor = array();
                foreach ($key as $or) {
                    list($num_new_scores) = $this->parseWord($or);//!!!
                    $has_new_scores |= $num_new_scores;//!!!
                    $queryor[] = $or;
                }
                if (count($queryor)) {
                    $this->conditions['OR'][] = $queryor;
                    // A group of OR keywords only needs to match once.
                    $this->matches += ($has_new_scores > 0);
                }
            }
            // Single ANDed term.
            else {
                $has_and = TRUE;
                list($num_new_scores, $num_valid_words) = $this->parseWord($key);
                $this->conditions['AND'][] = $key;
                if (!$num_valid_words) {
                    $this->simple = FALSE;
                }
                // Each AND keyword needs to match at least once.
                $this->matches += $num_new_scores;
            }
        }
        if ($has_and && $has_or) {
            $this->simple = FALSE;
        }

        // Negative matches.
        foreach ($this->keys['negative'] as $key) {
            $this->conditions['NOT'][] = $key;
            $this->simple = FALSE;
        }
    }

    /**
     * Sets the entered words to $this->words, if it is not already in there.
     * Increases the 'new-scores' variable if there was addad a new word to §this->words and checks if the word is valid, that means if the minimum wordsize is reached.
     * Splits also phrase entires into words.
     * num_new_scores is important for the matches in SQL later.
     */
    protected function parseWord($word) {
        $num_new_scores = 0;
        $num_valid_words = 0;

        // Determine the scorewords of this word/phrase.
        $split = explode(' ', $word);
        foreach ($split as $s) {
            $num = is_numeric($s);
            if ($num || strlen($s) >= $this->minimum_word_size) {
                if (!isset($this->words[$s])) {
                    $this->words[$s] = $s;
                    $num_new_scores++;
                }
                $num_valid_words++;
            }
        }

        // Return matching snippet and number of added words.
        return array($num_new_scores, $num_valid_words);
    }

    protected function highlightText($object)
    {
        //highlight title
        $title = $object->getTitle();
        $allTitelsWords = explode(" ", $title);
        $wordsToHighlightTitle = array();
        foreach($allTitelsWords as $allTitelsWord) {
            $simplifiedWord = $this->util->searchSimplify($allTitelsWord);
            foreach($this->words as $searchWord){
                if($searchWord == $simplifiedWord) {
                    $wordsToHighlightTitle[$allTitelsWord] = $simplifiedWord;
                }
            }
        }
        $newTitle = $title;
        foreach($wordsToHighlightTitle as $key => $value){
            $newTitle = str_replace($key, '<b style="color:red">'.$key.'</b>', $newTitle);
        }
        if($newTitle != null) {
            $object->setTitle($newTitle);
        }

        //highlight teaser
        $teaser = $object->getTeaser();
        $allTeasersWords = explode(" ", $teaser);
        $wordsToHighlightTeaser = array();
        foreach($allTeasersWords as $allTeasersWord) {
            $simplifiedWord = $this->util->searchSimplify($allTeasersWord);
            foreach($this->words as $searchWord){
                if($searchWord == $simplifiedWord) {
                    $wordsToHighlightTeaser[$allTeasersWord] = $simplifiedWord;
                }
            }
        }
        $newTeaser = $teaser;
        foreach($wordsToHighlightTeaser as $key => $value){
            $newTeaser = str_replace($key, '<b style="color:red">'.$key.'</b>', $newTeaser);
        }
        if($newTeaser != null) {
            $object->setTeaser($newTeaser);
        }

        return $object;
    }

    public function hasToManyExpressions()
    {
        return $this->toManyExpressions;
    }

}