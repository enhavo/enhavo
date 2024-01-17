<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Template\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class LoremIpsumFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private readonly LoremIpsumGenerator $loremIpsumGenerator,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('lorem_ipsum', function (bool $html = false, int|array $paragraphs = 1, int|array $sentences = [3, 8], int|array $words = [3, 10], int|array $chars = [2,12], int $commaChance = 33): string {
                return '(new LoremIpsumGenerator)->generate($html, $words, $sentences, $paragraphs, $chars, $commaChance)';
            }, function ($arguments, bool $html = false, int|array $paragraphs = 1, int|array $sentences = [3, 8], int|array $words = [3, 10], int|array $chars = [2,12], int $commaChance = 33): string {
                return $this->loremIpsumGenerator->generate($html, $paragraphs, $sentences, $words, $chars, $commaChance);
            }),
        ];
    }
}
