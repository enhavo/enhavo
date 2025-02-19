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

    public function getVariables(): array
    {
        return ['user' => $this->tokenStorage->getToken()?->getUser()];
    }
}
