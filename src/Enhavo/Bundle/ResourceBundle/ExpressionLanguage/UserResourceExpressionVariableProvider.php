<?php

namespace Enhavo\Bundle\ResourceBundle\ExpressionLanguage;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserResourceExpressionVariableProvider implements ResourceExpressionVariableProviderInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
    )
    {
    }

    public function getVariableName(): string
    {
        return 'user';
    }

    public function getVariableValue(): mixed
    {
        return $this->tokenStorage->getToken()?->getUser();
    }
}
