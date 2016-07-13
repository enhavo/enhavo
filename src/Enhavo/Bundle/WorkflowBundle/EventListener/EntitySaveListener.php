<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * checks if the given resource has to get published or unpublished according to the belonging workflowstatus
 */
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
        //find all workflows
        $workflowRepository = $this->em->getRepository('EnhavoWorkflowBundle:Workflow');
        $allWF = $workflowRepository->findAll();

        $workflow = null;

        //check if the class of the subject(entity) is in the array of a workflow entity column
        foreach($allWF as $currentWorkflow){
            if(in_array(get_class($event->getSubject()), $currentWorkflow->getEntity())){
                //this workflow belongs to the given entity(subject)
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

                $currentEntity = $event->getSubject();
                if(method_exists($currentEntity, 'getPublic')) { //check if public field exist

                    //check if the node is a end node, if it is set the public field (if it exists) to true
                    if($currentNode->getEnd()) {
                        $currentEntity->setPublic(true);
                    } else {
                        $currentEntity->setPublic(false);
                    }
                }
                $this->em->flush();
            }
        }
    }
}