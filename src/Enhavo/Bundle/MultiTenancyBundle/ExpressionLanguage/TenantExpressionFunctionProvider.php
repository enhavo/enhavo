<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.09.18
 * Time: 10:49
 */

namespace Enhavo\Bundle\MultiTenancyBundle\ExpressionLanguage;

use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class TenantExpressionFunctionProvider implements ExpressionFunctionProviderInterface
{
    /** @var ResolverInterface */
    private $resolver;

    /**
     * MultiTenancyResolverExpressionLanguageProvider constructor.
     * @param ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function getFunctions()
    {
        return [
            new ExpressionFunction('tenancy', function () {
                return '$this->getTenant()';
            }, function () {
                return $this->resolver->getTenant()->getKey();
            }),
        ];
    }
}
