<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Template\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class UrlFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private readonly ?string $prefix,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('url', function ($value): string {
                return $value;
            }, function ($arguments, $value): string {
                if ($this->prefix) {
                    return $this->prefix.$value;
                }
                return $value;
            }),
        ];
    }
}
