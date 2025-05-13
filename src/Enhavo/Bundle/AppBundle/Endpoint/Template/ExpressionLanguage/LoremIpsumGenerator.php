<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Endpoint\Template\ExpressionLanguage;

class LoremIpsumGenerator
{
    private array $words = [
        'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'praesent', 'interdum', 'dictum',
        'mi', 'non', 'egestas', 'nulla', 'in', 'lacus', 'sed', 'sapien', 'placerat', 'malesuada', 'at', 'erat', 'etiam',
        'id', 'velit', 'finibus', 'viverra', 'maecenas', 'mattis', 'volutpat', 'justo', 'vitae', 'vestibulum', 'metus',
        'lobortis', 'mauris', 'luctus', 'leo', 'feugiat', 'nibh', 'tincidunt', 'a', 'integer', 'facilisis', 'lacinia',
        'ligula', 'ac', 'suspendisse', 'eleifend', 'nunc', 'nec', 'pulvinar', 'quisque', 'ut', 'semper', 'auctor',
        'tortor', 'mollis', 'est', 'tempor', 'scelerisque', 'venenatis', 'quis', 'ultrices', 'tellus', 'nisi', 'phasellus',
        'aliquam', 'molestie', 'purus', 'convallis', 'cursus', 'ex', 'massa', 'fusce', 'felis', 'fringilla', 'faucibus',
        'varius', 'ante', 'primis', 'orci', 'et', 'posuere', 'cubilia', 'curae', 'proin', 'ultricies', 'hendrerit', 'ornare',
        'augue', 'pharetra', 'dapibus', 'nullam', 'sollicitudin', 'euismod', 'eget', 'pretium', 'vulputate', 'urna', 'arcu',
        'porttitor', 'quam', 'condimentum', 'consequat', 'tempus', 'hac', 'habitasse', 'platea', 'dictumst', 'sagittis',
        'gravida', 'eu', 'commodo', 'dui', 'lectus', 'vivamus', 'libero', 'vel', 'maximus', 'pellentesque', 'efficitur',
        'class', 'aptent', 'taciti', 'sociosqu', 'ad', 'litora', 'torquent', 'per', 'conubia', 'nostra', 'inceptos',
        'himenaeos', 'fermentum', 'turpis', 'donec', 'magna', 'porta', 'enim', 'curabitur', 'odio', 'rhoncus',
        'blandit', 'potenti', 'sodales', 'accumsan', 'congue', 'neque', 'duis', 'bibendum', 'laoreet', 'elementum',
        'suscipit', 'diam', 'vehicula', 'eros', 'nam', 'imperdiet', 'sem', 'ullamcorper', 'dignissim', 'risus',
        'aliquet', 'habitant', 'morbi', 'tristique', 'senectus', 'netus', 'fames', 'nisl', 'iaculis', 'cras', 'aenean',
    ];

    public function generate(bool $html = false, int|array $paragraphs = 1, int|array $sentences = [3, 8], int|array $words = [3, 10], int|array $chars = [2, 12], int $punctuationChance = 33): string
    {
        $wordsData = $this->getWords($chars);
        $paragraphData = [];
        $nParagraphs = $this->getNumber($paragraphs, 'paragraphs');
        for ($p = 0; $p < $nParagraphs; ++$p) {
            $nSentences = $this->getNumber($sentences, 'sentences');
            $sentenceData = [];
            for ($s = 0; $s < $nSentences; ++$s) {
                $nWords = $this->getNumber($words, 'words');
                $wordData = $this->getRandomWords($nWords, $wordsData);
                $wordData = $this->generateComma($wordData, $punctuationChance);
                $sentenceData[] = ucfirst(implode(' ', $wordData)).(0 == $punctuationChance ? '' : '.');
            }
            $paragraphData[] = implode(' ', $sentenceData);
        }

        if ($html) {
            $paragraphData = array_map(function ($value) {
                return '<p>'.$value.'</p>';
            }, $paragraphData);
        }

        return implode("\n\n", $paragraphData);
    }

    private function getNumber(int|array $value, $name)
    {
        if (is_array($value)) {
            if (2 != count($value) || !isset($value[0], $value[1]) || !is_int($value[0]) || !is_int($value[1]) || $value[0] < 0 || $value[1] < 0) {
                throw new \Exception(sprintf('Number of %s must be an array with exact two positive integers. The min and max value, but "[%s]" given', $name, implode(',', $value)));
            }

            return random_int($value[0], $value[1]);
        }

        return $value;
    }

    private function getWords(int|array $chars): array
    {
        $min = 2;
        $max = 12;
        if (is_array($chars)) {
            if (2 != count($chars) || !isset($chars[0], $chars[1]) || !is_int($chars[0]) || !is_int($chars[1]) || $chars[0] < $min || $chars[1] > $max) {
                throw new \Exception(sprintf('Number of chars must be an array with two integers. The min not less then %s and max value not more than %s, but "[%s]" given', $min, $max, implode(',', $chars)));
            }

            $min = $chars[0];
            $max = $chars[1];
        } else {
            $min = $chars;
            $max = $chars;
        }

        $words = [];
        foreach ($this->words as $word) {
            if (strlen($word) >= $min && strlen($word) <= $max) {
                $words[] = $word;
            }
        }

        return $words;
    }

    private function getRandomWords(int $count, array $words): array
    {
        $wordsData = [];
        for ($i = 0; $i < $count; ++$i) {
            $randomIndex = random_int(0, count($words) - 1);
            $wordsData[] = $words[$randomIndex];
        }

        return $wordsData;
    }

    private function generateComma($wordData, $commaChance): array
    {
        $value = random_int(1, 100);
        $space = 2;

        $nWords = count($wordData);
        if ($nWords < ($space * 2 + 1)) {
            return $wordData;
        } elseif ($value > $commaChance) {
            return $wordData;
        }

        $index = random_int($space - 1, $nWords - $space - 1);
        $wordData[$index] = $wordData[$index].',';

        return $wordData;
    }
}
