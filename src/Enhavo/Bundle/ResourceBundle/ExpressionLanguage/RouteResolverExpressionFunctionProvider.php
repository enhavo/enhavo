<?php

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class RouteResolverExpressionFunctionProvider implements ResourceExpressionFunctionProviderInterface
{
    public function __construct(
        private readonly RouteResolverInterface $routeResolver,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction(
                'resolve_route',
                function () {
                    return '$routeResolver->resolve()';
                },
                function ($args, string $name, array $context = []) {
                    return $this->routeResolver->getRoute($name, $context);
                }
            )
        ];
    }
}
