<?php

namespace Enhavo\Bundle\WorkflowBundle\Security\Authentication\Voter;

use Enhavo\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;

class WorkflowVoter  implements VoterInterface
{
    protected $manager;
    protected $container;

    public function __construct(ObjectManager $manager, $container)
    {
        $this->container = $container;
        $this->manager = $manager;
    }

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
        $roleUtil = new RoleUtil();
        $action = $roleUtil->getAction($attributes[0]);

        if (is_object($object) && $action == RoleUtil::ACTION_UPDATE) {
            $repository = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow');
            if ($repository->hasActiveWorkflow($object)) {
                if ($this->isAllowed($object, $token)) {
                     return self::ACCESS_GRANTED;
                } else {
                    return self::ACCESS_DENIED;
                }
            } else {
                return self::ACCESS_ABSTAIN;
            }
        }
       return self::ACCESS_ABSTAIN;
    }

    protected function isAllowed($resource, $token) {

        //check if the user is allowed to edit the clicked element
        $user = $token->getUser();
        if($user instanceof User){
            $workflowStatus = $resource->getWorkflowStatus();
            $transitions = $this->manager->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                'node_from' => $workflowStatus->getNode()
            ));
            if ($user->getGroups()) {
                foreach ($transitions as $transition) {
                    $transGroups = $transition->getGroups();
                    foreach ($transGroups as $transGroup) {
                        if ($user->hasGroup($transGroup->getName())) {
                            //the user is allowed to edit
                            return true;
                        }
                    }
                }
            }
        }
        //the user is not allowed ot edit
        return false;
    }
}