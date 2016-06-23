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

class HtmlType extends AbstractIndexType
{
    function index($val, $options)
    {
        //indexing html text and save in DB
        //get weights of words
        $tagYaml = $this->util->getMainPath().'/Enhavo/Bundle/SearchBundle/Resources/config/tag_weights.yml';
        $yaml = new Parser();
        $tags = $yaml->parse(file_get_contents($tagYaml));
        if($options['weights'] != null) //set given weights to default weights
        {
            foreach ($options['weights'] as $key => $value) {
                if(array_key_exists($key, $tags)) {
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
        $accum = ' '; // Accumulator for cleaned up data
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
                    }
                    else {
                        // Remove from tag stack and decrement score
                        $score = max(1, $score - $tags[array_shift($tagstack)]);
                    }
                }
                else {
                    if (isset($tagstack[0]) && $tagstack[0] == $tagname) {
                        // None of the tags we look for make sense when nested identically.
                        // If they are, it's probably broken HTML.
                        $tagstack = array();
                        $score = 1;
                    }
                    else {
                        // Add to open tag stack and increment score
                        array_unshift($tagstack, $tagname);
                        $score += $tags[$tagname];
                    }
                }
                // A tag change occurred, reset counter.
                $tagwords = 0;
            }
            else {
                // Note: use of PREG_SPLIT_DELIM_CAPTURE above will introduce empty values
                if ($value != '') {
                    $words = $this->searchIndexSplit($value);
                    foreach ($words as $word) {
                        if($word != "") {
                            // Add word to accumulator
                            $accum .= $word . ' ';
                            // Check wordlength
                            if (is_numeric($word) || strlen($word) >= $minimumWordSize) {
                                if (!isset($scoredWords[$word])) {
                                    $scoredWords[$word] = 0;
                                }
                                $scoredWords[$word] += $score * $focus;
                                // Focus is a decaying value in terms of the amount of unique words up to this point.
                                // From 100 words and more, it decays, to e.g. 0.5 at 500 words and 0.3 at 1000 words.
                                $focus = min(1, .01 + 3.5 / (2 + count($scoredWords) * .015));
                            }
                            $tagwords++;
                            // Too many words inside a single tag probably mean a tag was accidentally left open.
                            if (count($tagstack) && $tagwords >= 15) {
                                $tagstack = array();
                                $score = 1;
                            }
                        }
                    }
                }
            }
            $tag = !$tag;
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
        return 'Html';
    }

}