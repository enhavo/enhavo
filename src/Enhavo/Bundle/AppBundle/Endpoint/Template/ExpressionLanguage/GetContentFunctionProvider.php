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

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class GetContentFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private readonly string $dataPath,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('get_content', function ($file): string {
                return '';
            }, function ($arguments, $file): string {
                $path = $this->dataPath.'/'.$file;
                if (!file_exists($path)) {
                    throw new \Exception(sprintf('Can\'t find file "%s"', $file));
                }

                return file_get_contents($path);
            }),
        ];
    }
}
