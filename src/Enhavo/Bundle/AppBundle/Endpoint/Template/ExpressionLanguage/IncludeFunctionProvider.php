<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Template\ExpressionLanguage;

use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class IncludeFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private Loader $loader,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('include', function ($file): string {
                return '$loader->load($file)';
            }, function ($arguments, $file): mixed {
                return $this->loader->load($file);
            }),
        ];
    }
}
