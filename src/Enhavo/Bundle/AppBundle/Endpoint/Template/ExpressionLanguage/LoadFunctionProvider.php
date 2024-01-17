<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Template\ExpressionLanguage;

use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class LoadFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private Loader $loader,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('load', function (string|array $files, bool $recursive = false, ?int $depth = null): string {
                return '$loader->load($files, $recursive, $depth)';
            }, function ($arguments, string|array $files, bool $recursive = false, ?int $depth = null): mixed {
                return $this->loader->load($files, $recursive, $depth);
            }),
        ];
    }
}
