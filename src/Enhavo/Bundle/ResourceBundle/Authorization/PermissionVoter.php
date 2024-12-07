<?php

namespace Enhavo\Bundle\ResourceBundle\Authorization;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class PermissionVoter implements VoterInterface
{
    public function __construct(
        private readonly AccessDecisionManagerInterface $accessDecisionManager,
    ) {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        if (isset($attributes[0]) && $attributes[0] instanceof Permission) {
            return $this->accessDecisionManager->decide($token, [$this->getRole($attributes[0])]);
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    private function getRole(Permission $permission): string
    {
        if (str_starts_with($permission->name, 'ROLE_')) {
            return $permission->action;
        }

        $role = str_replace('.', '_', $permission->name);
        $role = str_replace('-', '_', $role);
        $role = 'ROLE_' . $role . '_' . $permission->action;
        return strtoupper($role);
    }
}
