<?php

namespace Enhavo\Bundle\AppBundle\Security\Authentication\Voter;

use Enhavo\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;

class SuperAdminVoter  implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return 0 === strpos($attribute, 'ROLE_');
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if(0 === strpos($attributes[0], 'ROLE_')){
            if($token->getUser() instanceof User && in_array('ROLE_SUPER_ADMIN', $token->getUser()->getRoles())) {
                return self::ACCESS_GRANTED;
            }
        }
        return self::ACCESS_ABSTAIN;
    }
}