<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;

class EntitySaveListener
{
    protected $em;

    protected $workflowClass;

    public function __construct(EntityManager $em, $workflowClass)
    {
        $this->em = $em;
        $this->workflowClass = $workflowClass;
    }

    public function onSave(GenericEvent $event)
    {
        //get workflow of current entity
        $workflowRepository = $this->em->getRepository('EnhavoWorkflowBundle:Workflow');
        $workflow = $workflowRepository->findOneBy(array(
            'entity' => get_class($event->getSubject())
        ));

        //if there is a workflow created for the current entity then check the things below
        //if workflow is null, there is no workflow created for this entity
        if($workflow != null){

            //get the current workflow-status of the event
            $currentWorkflowStatus = $event->getSubject()->getWorkflowStatus();
            if($currentWorkflowStatus != null){

                //get the node of the current workflow-status
                $currentNode = $currentWorkflowStatus->getNode();
                if($currentNode == null) {

                    //set creationNode  if the current workflow-status has no node yet
                    $nodes = $workflow->getNodes();
                    foreach($nodes as $node) {
                        if($node->getStart()) {
                            $currentWorkflowStatus->setNode($node);
                            $currentNode = $currentWorkflowStatus->getNode();
                            break;
                        }
                    }
                }

                //check if the node is a end node, if it is set the public field (if it exists) to true
                if($currentNode->getEnd()){

                    //is end-node
                    $current = $event->getSubject();

                    //check if public field exist end set true if it does
                    if(method_exists($current, 'getPublic')){
                        $current->setPublic(true);
                        $this->em->persist($current);
                        $this->em->flush();
                    }
                } else {

                    //is no end-node
                    $current = $event->getSubject();

                    //check if public field exists and set to false if it does
                    if(method_exists($current, 'getPublic')){
                        $current->setPublic(false);
                        $this->em->persist($current);
                        $this->em->flush();
                    }
                }
            }
        }
    }
}