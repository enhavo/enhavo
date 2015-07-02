<?php
/**
 * GroupRoleVoter.php
 *
 * @since 24/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Enhavo\Bundle\UserBundle\Entity\User;

class GroupRoleVoter  implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return true;
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $user = $token->getUser();
        if($user instanceof User) {
            if($user->getGroups()) {
                foreach($user->getGroups() as $group) {
                    if($group->hasRole($attributes[0])) {
                        return VoterInterface::ACCESS_GRANTED;
                    }
                }
            }
        }
        return VoterInterface::ACCESS_DENIED;
    }
} 