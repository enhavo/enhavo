<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.09.18
 * Time: 10:49
 */

namespace Enhavo\Bundle\MultiTenancyBundle\ExpressionLanguage;

use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;


class TenantExpressionFunctionProvider implements ResourceExpressionFunctionProviderInterface
{
    public function __construct(
        private ResolverInterface $resolver
    )
    {
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
