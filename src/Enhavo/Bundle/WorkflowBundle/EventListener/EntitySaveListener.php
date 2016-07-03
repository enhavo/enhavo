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
        $workflowRepository = $this->em->getRepository('EnhavoWorkflowBundle:Workflow');
        $allWF = $workflowRepository->findAll();
        $workflow = null;
        foreach($allWF as $currentWorkflow){
            if(in_array(get_class($event->getSubject()), $currentWorkflow->getEntity())){
                $workflow = $currentWorkflow;
            }
        }
        //if there is a workflow created for the current entity then check the things below
        //if workflow is null, there is no workflow created for this entity
        if(!empty($workflow)){

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

                $current = $event->getSubject();
                if(method_exists($current, 'getPublic')) { //check if public field exist
                    //check if the node is a end node, if it is set the public field (if it exists) to true
                    if($currentNode->getEnd()) {
                        $current->setPublic(true);
                    } else {
                        $current->setPublic(false);
                    }
                }
                $this->em->flush();
            }
        }
    }
}