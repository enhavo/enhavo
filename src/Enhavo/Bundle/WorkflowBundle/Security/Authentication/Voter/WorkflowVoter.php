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
        if($attributes[0] == 'WORKFLOW'){
            if($user instanceof User) {
                if (is_object($object)){
                    $array = explode('\\', get_class($object));
                    $entity = array_pop($array);
                    $workflow = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findOneBy(array(
                        'entity' => $entity,
                    ));
                    if ($workflow != null) {
                        $nodes = $this->manager->getRepository('EnhavoWorkflowBundle:Node')->findBy(array(
                            'workflow' => $workflow
                        ));
                        $workflowStatus = $object->getWorkflowStatus();
                        $transitions = $this->manager->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                            'node_from' => $workflowStatus->getNode()
                        ));
                        if ($user->getGroups()) {
                            foreach ($transitions as $transition) {
                                $transGroups = $transition->getGroups();
                                foreach ($transGroups as $transGroup) {
                                    if ($user->hasGroup($transGroup->getName())) {
                                        return VoterInterface::ACCESS_GRANTED;
                                    }
                                }
                            }
                        }
                        return VoterInterface::ACCESS_DENIED;
                    }
                } else if(array_key_exists('entity', $object)){
                    //check if user is allowed to create a ... for this workflow
                    $entity = $object['entity'];
                    $workflow = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findOneBy(array(
                        'entity' => $entity,
                    ));
                    if($workflow != null) {
                        $transitions = $this->manager->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                            'workflow' => $workflow,
                        ));
                        $creationGroups = array();
                        foreach($transitions as $transition) {
                            $nodeFrom = $transition->getNodeFrom();
                            if($nodeFrom->getNodeName() == 'creation') {
                                foreach($transition->getGroups() as $group){
                                    $creationGroups[] = $group;
                                }
                            }
                        }
                        foreach($creationGroups as $creationGroup) {
                            if($user->hasGroup($creationGroup->getName())) {
                                return VoterInterface::ACCESS_GRANTED;
                            }
                        }
                        return VoterInterface::ACCESS_DENIED;
                    }
                }
            }
        }
        return VoterInterface::ACCESS_GRANTED;
    }
}