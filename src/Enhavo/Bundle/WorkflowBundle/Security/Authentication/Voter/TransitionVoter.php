<?php

namespace Enhavo\Bundle\WorkflowBundle\Security\Authentication\Voter;

use Enhavo\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;

class TransitionVoter  implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return true;
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $transition, array $attributes)
    {
        if(in_array('WORKFLOW_TRANSITION', $attributes)){

            //check if the current user is allowed to use the transition
            $user = $token->getUser();
            $userGroupsCol = $user->getGroups();

            //make array of collection
            $userGroups = array();
            foreach ($userGroupsCol as $userGroup) {
                $userGroups[] = $userGroup;
            }

            $transitionGroupsCol = $transition->getGroups();

            //make array of collection
            $transitionGroups = array();
            foreach ($transitionGroupsCol as $transitionGroup) {
                $transitionGroups[] = $transitionGroup;
            }

            foreach ($userGroups as $userGroup) {
                if(in_array($userGroup, $transitionGroups))
                {
                    return self::ACCESS_GRANTED;
                }
            }
            return self::ACCESS_DENIED;
        }
        return self::ACCESS_ABSTAIN;
    }
}