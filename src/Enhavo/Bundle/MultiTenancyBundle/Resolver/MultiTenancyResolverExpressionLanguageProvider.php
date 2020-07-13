<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.09.18
 * Time: 10:49
 */

namespace Bundle\MultiTenancyBundle\Resolver;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class MultiTenancyResolverExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    /**
     * @var MultiTenancyResolver
     */
    private $multiTenancyResolver;

    /**
     * MultiTenancyResolverExpressionLanguageProvider constructor.
     * @param MultiTenancyResolver $multiTenancyResolver
     */
    public function __construct(MultiTenancyResolver $multiTenancyResolver)
    {
        $this->multiTenancyResolver = $multiTenancyResolver;
    }

    public function getFunctions()
    {
        $multiTenancyResolver = $this->multiTenancyResolver;
        return array(
            new ExpressionFunction('get_multiTenancy', function () {
                return '$this->getMultiTenancy()';
            }, function () use ($multiTenancyResolver) {
                return $multiTenancyResolver->getKey();
            }),
        );
    }
}
