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

use Enhavo\Bundle\AppBundle\Endpoint\Template\Loader;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Twig\Environment;

class RenderFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private readonly Environment $twig,
        private readonly Loader $loader,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('render', function ($template, ...$loadFiles): string {
                return '';
            }, function ($arguments, $template, ...$loadFiles): string {
                $data = $this->loader->load($loadFiles);

                return $this->twig->render($template, $data);
            }),
        ];
    }
}
