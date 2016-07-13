<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Enhavo\Bundle\SearchBundle\Index\AbstractIndexType;
use Symfony\Component\Yaml\Parser;
use Enhavo\Bundle\SearchBundle\Index\IndexItem;

/*
 * Prepares fields of type html for indexing
 */
class HtmlType extends AbstractIndexType
{
    function index($val, $options, $properties = null)
    {
        //create a new IndexItem
        $indexItem = new IndexItem();

        //if rawData is set in properties or properties is empty -> set rawData in the IndexItem
        if(empty($properties) || in_array('rawData', $properties)){
            $indexItem->setRawData($val);
        }

        //if data or scoredWords is set in properties or properties is empty -> set data and scoredWords in the IndexItem
        if(empty($properties) || in_array('data', $properties) || in_array('scoredWords', $properties)) {
            $accum = ' ';

            //indexing html text and save in DB
            //get weights of words
            $tagYaml = $this->util->getMainPath() . '/Enhavo/Bundle/SearchBundle/Resources/config/tag_weights.yml';
            $yaml = new Parser();
            $tags = $yaml->parse(file_get_contents($tagYaml));
            if (isset($options['weights'])) //set given weights to default weights
            {
                foreach ($options['weights'] as $key => $value) {
                    if (array_key_exists($key, $tags)) {
                        $tags[$key] = $value;
                    } else {
                        $tags[$key] = $value;
                    }
                }
            }

            // Strip off all ignored tags, insert space before and after them to keep word boundaries.
            $val = str_replace(array('<', '>'), array(' <', '> '), $val);
            $val = strip_tags($val, '<' . implode('><', array_keys($tags)) . '>');

            // Split HTML tags from plain text.
            $split = preg_split('/\s*<([^>]+?)>\s*/', $val, -1, PREG_SPLIT_DELIM_CAPTURE);

            $tag = FALSE; // Odd/even counter. Tag or no tag.
            $score = 1; // Starting score per word
            $tagstack = array(); // Stack with open tags
            $tagwords = 0; // Counter for consecutive words
            $focus = 1; // Focus state
            $scoredWords = array();
            $minimumWordSize = $this->getMinimumWordSize($options);

            //go trough the array of text and tags
            foreach ($split as $value) {

                //if tag is true we are handling the tags in the array, if tag is false we are handling text between the tags
                if ($tag) {

                    // Increase or decrease score per word based on tag
                    list($tagname) = explode(' ', $value, 2);
                    $tagname = strtolower($tagname);

                    // Closing or opening tag?
                    if ($tagname[0] == '/') {
                        $tagname = substr($tagname, 1);

                        // If we encounter unexpected tags, reset score to avoid incorrect boosting.
                        if (!count($tagstack) || $tagstack[0] != $tagname) {
                            $tagstack = array();
                            $score = 1;
                        } else {

                            // Remove from tag stack and decrement score
                            $score = max(1, $score - $tags[array_shift($tagstack)]);
                        }
                    } else {
                        if (isset($tagstack[0]) && $tagstack[0] == $tagname) {

                            // None of the tags we look for make sense when nested identically.
                            // If they are, it's probably broken HTML.
                            $tagstack = array();
                            $score = 1;
                        } else {

                            // Add to open tag stack and increment score
                            array_unshift($tagstack, $tagname);
                            $score += $tags[$tagname];
                        }
                    }

                    // A tag change occurred, reset counter.
                    $tagwords = 0;
                } else {

                    // Note: use of PREG_SPLIT_DELIM_CAPTURE above will introduce empty values
                    if ($value != '') {
                        $words = $this->searchIndexSplit($value);
                        foreach ($words as $word) {

                            // Add word to accumulator
                            $accum .= $word . " ";

                            // Check wordlength
                            list($scoredWords, $focus) = $this->scoreWord($word, $score, $minimumWordSize, $scoredWords, $focus);
                            $tagwords++;

                            // Too many words inside a single tag probably mean a tag was accidentally left open.
                            if (count($tagstack) && $tagwords >= 15) {
                                $tagstack = array();
                                $score = 1;
                            }
                        }
                    }
                }
                $tag = !$tag;
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

            //set data and scored words
            $indexItem->setData(rtrim($accum));
            $indexItem->setScoredWords($indexItemArray);
        }

        //return the indexItem
        return array($indexItem);
    }

    /**
     * Returns a unique type name for this type
     *
     * @return string
     */
    public function getType()
    {
        return 'Html';
    }

}