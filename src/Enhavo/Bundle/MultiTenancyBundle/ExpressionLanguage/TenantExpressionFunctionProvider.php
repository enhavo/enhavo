<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\ExpressionLanguage;

use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class TenantExpressionFunctionProvider implements ResourceExpressionFunctionProviderInterface
{
    public function __construct(
        private ResolverInterface $resolver,
    ) {
    }

    public function getFunctions()
    {
        return [
            new ExpressionFunction('tenant', function () {
                return '$this->getTenant()';
            }, function () {
                return $this->resolver->getTenant()->getKey();
            }),
        ];
    }
}
