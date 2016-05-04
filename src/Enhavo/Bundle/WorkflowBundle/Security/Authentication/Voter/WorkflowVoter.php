<?php

namespace Enhavo\Bundle\WorkflowBundle\Security\Authentication\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Enhavo\Bundle\UserBundle\Entity\User;

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
        $user = $token->getUser();
        if($attributes[0] == 'WORKFLOW'){
            if($user instanceof User) {

                //check if the object variable is an object --> this means someone clicked on an element to UPDATE it
                if (is_object($object)){
                    $array = explode('\\', get_class($object));
                    $entity = array_pop($array);

                    //get the current workflow of the clicked element
                    $workflow = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findOneBy(array(
                        'entity' => $entity,
                    ));

                    //if the workflow is null it means there is no workflow belonging to this entity
                    //check more if there is a workflow and the workflow is also active
                    if ($workflow != null && $workflow->getActive()) {

                        //check if the user is allowed to edit the clicked element
                        $workflowStatus = $object->getWorkflowStatus();
                        $transitions = $this->manager->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                            'node_from' => $workflowStatus->getNode()
                        ));
                        if ($user->getGroups()) {
                            foreach ($transitions as $transition) {
                                $transGroups = $transition->getGroups();
                                foreach ($transGroups as $transGroup) {
                                    if ($user->hasGroup($transGroup->getName())) {
                                        //the user is allowed to edit
                                        return VoterInterface::ACCESS_GRANTED;
                                    }
                                }
                            }
                        }
                        //the user is not allowed ot edit
                        return VoterInterface::ACCESS_DENIED;
                    }
                } else if(is_array($object)){ //if the given object is an array, check if the CREATE-BUTTON should be displayed (it should only be displayed if the current user has the permission)
                    if(array_key_exists('entity', $object)) {

                        //check if user is allowed to create things for this entity
                        $entity = $object['entity'];
                        if ($entity != 'workflow') { //if the entity is not workflow

                            //check if a workflow for the current entity exists
                            $workflow = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findOneBy(array(
                                'entity' => $entity,
                            ));

                            //if it exists a workflow for the current entity and the workflow is also active, check the things below; if no workflow exists nothing happens
                            if ($workflow != null && $workflow->getActive() == true) {

                                //check if the user is allowed to create
                                $transitions = $this->manager->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                                    'workflow' => $workflow,
                                ));
                                $creationGroups = array();
                                foreach ($transitions as $transition) {
                                    $nodeFrom = $transition->getNodeFrom();
                                    if ($nodeFrom->getStart() == true) {
                                        foreach ($transition->getGroups() as $group) {
                                            $creationGroups[] = $group;
                                        }
                                    }
                                }
                                foreach ($creationGroups as $creationGroup) {
                                    if ($user->hasGroup($creationGroup->getName())) {

                                        //the current user is allowed to create
                                        return VoterInterface::ACCESS_GRANTED;
                                    }
                                }

                                //the current user is not allowed to create
                                return VoterInterface::ACCESS_DENIED;
                            }
                        } else {
                            //check if there are still possible entities without workflows --> if there are, display the create-button
                            $workflowRepository = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow');
                            $workflows = $workflowRepository->findAll();
                            $possibleEntities = $this->container->getParameter('enhavo_workflow.entities');
                            $entities = array();
                            foreach ($possibleEntities as $possibleEntity) {
                                $array = explode('\\', $possibleEntity);
                                $entityName = strtolower(array_pop($array));
                                $entityHasNoWF = true;
                                foreach ($workflows as $workflow) {
                                    if ($workflow->getEntity() == $entityName) {
                                        $entityHasNoWF = false;
                                    }
                                }
                                if ($entityHasNoWF == true) {
                                    $entities[] = $entityName;
                                }
                            }
                            if (empty($entities)) {
                                return VoterInterface::ACCESS_DENIED;
                            }
                            return VoterInterface::ACCESS_GRANTED;
                        }
                    }
                } else if(is_string($object)){ //if the given object is a string, check if the 'workflow section' should be displayed in the form
                    if(strpos($object, '\Entity') !== false) {
                        $array = explode('\\', $object);
                        $entity = array_pop($array);

                        //get the current workflow of the entity
                        $workflow = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findOneBy(array(
                            'entity' => $entity,
                        ));

                        //if there is a workflow for the current entity, check if the workflow is active; if there is no workflow, check if it is possible to create a workflow for this entity.
                        // If it is, do not display the 'workflow section' in the form yet because there is no workflow yet.
                        if ($workflow != null) {
                           if($workflow->getActive() == true) {
                               return VoterInterface::ACCESS_GRANTED;
                           } else {
                               return VoterInterface::ACCESS_DENIED;
                           }
                        } else {
                            $possible = false;
                            $possibleEntities = $this->container->getParameter('enhavo_workflow.entities');
                            foreach($possibleEntities as $possibleEntity){
                                $splittedEntityPath = explode('\\', $possibleEntity);
                                $pEntity = array_pop($splittedEntityPath);
                                if($entity == $pEntity){
                                    $possible = true;
                                }
                            }
                            if($possible == true) {
                                return VoterInterface::ACCESS_DENIED;
                            }
                        }
                    }
                }
            }
        }
        return VoterInterface::ACCESS_GRANTED;
    }
}