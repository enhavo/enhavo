<?php

namespace Enhavo\Bundle\WorkflowBundle\Security\Authentication\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Enhavo\Bundle\UserBundle\Entity\User;

class WorkflowVoter  implements VoterInterface
{
    protected $manager;

    public function __construct(ObjectManager $manager)
    {
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
        $user = $token->getUser();
        if($user instanceof User && is_object($attributes[0])) {
            $array = explode('\\',get_class($attributes[0]));
            $entity = array_pop($array);
            $workflow = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findOneBy(array(
                'entity' => $entity,
            ));
            if($workflow != null){
                $nodes = $this->manager->getRepository('EnhavoWorkflowBundle:Node')->findBy(array(
                    'workflow' => $workflow
                ));
                $allWorkflowStatus = $this->manager->getRepository('EnhavoWorkflowBundle:WorkflowStatus')->findAll();
                $workflowStatus = null;
                foreach($allWorkflowStatus as $currentWorkflowStatus){
                    foreach($nodes as $node) {
                        if($currentWorkflowStatus->getNode() == $node) {
                            $workflowStatus = $currentWorkflowStatus;
                        }
                    }
                }
                $transitions = $this->manager->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                   'node_from' => $workflowStatus->getNode()
                ));
                if($user->getGroups()) {
                    foreach($transitions as $transition) {
                        $transGroups = $transition->getGroups();
                        foreach($transGroups as $transGroup){
                            if($user->hasGroup($transGroup->getName())){
                                return VoterInterface::ACCESS_GRANTED;
                            }
                        }
                    }
                }
            }
        }
        return VoterInterface::ACCESS_DENIED;
    }
}