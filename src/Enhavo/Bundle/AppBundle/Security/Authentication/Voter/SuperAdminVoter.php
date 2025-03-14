<?php

namespace Enhavo\Bundle\AppBundle\Security\Authentication\Voter;

use Enhavo\Bundle\UserBundle\Model\User;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SuperAdminVoter implements VoterInterface
{
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        if (is_string($attributes[0]) && str_starts_with($attributes[0], 'ROLE_')){
            if($token->getUser() instanceof User && in_array('ROLE_SUPER_ADMIN', $token->getUser()->getRoles())) {
                return self::ACCESS_GRANTED;
            }
        }
        return self::ACCESS_ABSTAIN;
    }
}
