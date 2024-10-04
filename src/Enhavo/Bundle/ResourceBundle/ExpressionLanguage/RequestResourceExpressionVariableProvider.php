<?php

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestResourceExpressionVariableProvider implements ResourceExpressionVariableProviderInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
    )
    {
    }

    public function getVariableName(): string
    {
        return 'request';
    }

    public function getVariableValue(): mixed
    {
        return $this->requestStack->getMainRequest();
    }
}
