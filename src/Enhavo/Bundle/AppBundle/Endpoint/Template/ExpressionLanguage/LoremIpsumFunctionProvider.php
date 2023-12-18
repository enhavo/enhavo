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
            new ExpressionFunction('lorem_ipsum', function ($length = 1): string {
                return '(new LoremIpsumGenerator)->generate($length)';
            }, function ($arguments, $length = 1): string {
                return $this->loremIpsumGenerator->generate($length);
            }),
        ];
    }
}
