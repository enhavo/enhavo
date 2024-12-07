<?php
/**
 * GroupRoleVoter.php
 *
 * @since 24/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication\Voter;

use Enhavo\Bundle\UserBundle\Model\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class GroupRoleVoter implements VoterInterface
{
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $user = $token->getUser();
        if($user instanceof User) {
            if($user->getGroups()) {
                foreach($user->getGroups() as $group) {
                    if(is_string($attributes[0]) && preg_match('/^ROLE_.*/', $attributes[0])) {
                        if ($group->hasRole($attributes[0])) {
                            return VoterInterface::ACCESS_GRANTED;
                        }
                    }
                }
            }
        }
        return VoterInterface::ACCESS_ABSTAIN;
    }
}
