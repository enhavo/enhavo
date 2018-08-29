<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 31.05.16
 * Time: 12:01
 */

namespace Enhavo\Bundle\SearchBundle\Util;

/*
 * This class highlights a given resource
 */
class Highlighter
{
    /**
     * @var TextSimplify
     */
    private $textSimplify;

    public function __construct()
    {
        $this->textSimplify = new TextSimplify();
    }

    public function highlight($text, array $words, $textLength = 160, $startTag = '<span class"highlight">', $closeTag = '</span>', $concat = ' ... ')
    {
        $textWords = preg_split("/[\s]+/", $text);
        $searchWords = [];
        foreach($words as $word) {
            $searchWords[] = $this->textSimplify->simplify($word);
        }

        $matches = [];
        for($i = 0; $i < count($textWords); $i++) {
            if(in_array($this->textSimplify->simplify($textWords[$i]), $searchWords)) {
                $match = new HighlightMatch();
                $match->setBehind($this->lookBehind($textWords, $i));
                $match->setForward($this->lookForward($textWords, $i));
                $match->setWord($textWords[$i]);
                $i += count($match->getForward());
                $matches[] = $match;
            }
        }

        if(empty($match)) {
            return '';
        }

        return $this->concatMatches($matches, $searchWords, $textLength, $startTag, $closeTag, $concat);
    }

    private function lookBehind($textWords, $i, $length = 10)
    {
        $behind = [];
        for($j = 0; $j < $length; $j++) {
            if(isset($textWords[$i-($j+1)])) {
                $word = $textWords[$i-($j+1)];
                if(preg_match('/[.!?:;]/', $word)) {
                    break;
                } else {
                    $behind[] = $word;
                }
            }
        }
        return array_reverse($behind);
    }

    private function lookForward($textWords, $i, $length = 10)
    {
        $forward = [];
        for($j = 0; $j < $length; $j++) {
            if(isset($textWords[$i+$j+1])) {
                $word = $textWords[$i+$j+1];
                if(preg_match('/[.!?:;]/', $word)) {
                    $forward[] = $word;
                    break;
                } else {
                    $forward[] = $word;
                }
            }
        }
        return array_reverse($forward);
    }

    /**
     * @param HighlightMatch[] $matches
     * @param string[] $searchWords
     * @param integer $textLength
     * @param $startTag
     * @param $closeTag
     * @param $concat
     * @return string
     */
    private function concatMatches($matches, $searchWords, $textLength, $startTag, $closeTag, $concat)
    {
        $lengthPerMatch = intval($textLength / count($matches));

        $sequences = [];
        $sequenceLengthTotal = 0;
        foreach($matches as $match) {
            $sequenceLength = 0;
            $sequence = $this->highlightWord($match->getWord(), $searchWords, $startTag, $closeTag);
            $sequenceLength += strlen($match->getWord()) + 1;

            do {
                $behind = $match->popBehind();
                if($behind !== null) {
                    $sequenceLength += strlen($behind) + 1;
                    $sequence = sprintf('%s %s', $this->highlightWord($behind, $searchWords, $startTag, $closeTag), $sequence);
                }

                $forward = $match->popForward();
                if($forward !== null) {
                    $sequenceLength += strlen($forward) + 1;
                    $sequence = sprintf('%s %s', $sequence, $this->highlightWord($forward, $searchWords, $startTag, $closeTag));
                }

                if(count($match->getBehind()) === 0 && count($match->getForward()) === 0) {
                    break;
                }
            } while($lengthPerMatch >= $sequenceLength);

            $sequences[] = $sequence;
            $sequenceLengthTotal += $sequenceLength;

            if($sequenceLengthTotal > $textLength) {
                break;
            }
        }
        return implode($concat, $sequences);
    }

    private function highlightWord($word, $searchWords, $startTag, $closeTag)
    {
        if(in_array($this->textSimplify->simplify($word), $searchWords)) {
            return $startTag.$word.$closeTag;
        }
        return $word;
    }
}