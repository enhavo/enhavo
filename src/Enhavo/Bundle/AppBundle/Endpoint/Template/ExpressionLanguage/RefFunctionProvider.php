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
use Symfony\Component\PropertyAccess\PropertyAccessor;

class RefFunctionProvider implements ExpressionFunctionProviderInterface
{
    private PropertyAccessor $propertyAccessor;

    public function __construct(
        private Loader $loader,
    ) {
        $this->propertyAccessor = new PropertyAccessor();
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('ref', function ($file, $property): mixed {
                return '';
            }, function ($arguments, $file, $property): mixed {
                $data = $this->loader->load($file);
                $propertyPath = explode('.', $property);
                $propertyPath = array_map(function ($value) {
                    return '['.$value.']';
                }, $propertyPath);

                return $this->propertyAccessor->getValue($data, implode('', $propertyPath));
            }),
        ];
    }
}
