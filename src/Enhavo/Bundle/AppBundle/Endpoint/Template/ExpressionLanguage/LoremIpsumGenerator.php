<?php

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
        'aliquet', 'habitant', 'morbi', 'tristique', 'senectus', 'netus', 'fames', 'nisl', 'iaculis', 'cras', 'aenean'
    ];

    public function generate($nparagraphs = 1): string
    {
        $paragraphs = [];
        for ($p = 0; $p < $nparagraphs; ++$p) {
            $nSentences = random_int(3, 8);
            $sentences = [];
            for ($s = 0; $s < $nSentences; ++$s) {
                $frags = [];
                $commaChance = .33;
                while (true) {
                    $nWords = random_int(3, 15);
                    $words = $this->getRandomWords($nWords);
                    $frags[] = implode(' ', $words);
                    if ($this->getRandomFloat() >= $commaChance) {
                        break;
                    }
                    $commaChance /= 2;
                }

                $sentences[] = ucfirst(implode(', ', $frags)) . '.';
            }
            $paragraphs[] = implode(' ', $sentences);
        }
        return implode("\n\n", $paragraphs);
    }

    private function getRandomFloat(): float
    {
        return random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX;
    }

    private function getRandomWords($count): array
    {
        $keys = array_rand($this->words, $count);
        if ($count == 1) {
            $keys = [$keys];
        }
        return array_intersect_key($this->words, array_fill_keys($keys, null));
    }
}
