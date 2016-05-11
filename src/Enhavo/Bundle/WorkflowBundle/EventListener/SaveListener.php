<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus;
use Symfony\Component\EventDispatcher\GenericEvent;

class SaveListener
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
                        if($node->getStart() == true) {
                            $currentWorkflowStatus->setNode($node);
                            $currentNode = $currentWorkflowStatus->getNode();
                            break;
                        }
                    }
                }

                //check if the node is a end node, if it is set the public field (if it exists) to true
                if($currentNode->getEnd() == true){

                    //is end-node
                    $current = $event->getSubject();

                    //check if public field exist end set true if it does
                    if(property_exists($current, 'public')){
                        $current->setPublic(true);
                        $this->em->persist($current);
                        $this->em->flush();
                    }
                } else {

                    //is no end-node
                    $current = $event->getSubject();

                    //check if public field exists and set to false if it does
                    if(property_exists($current, 'public')){
                        $current->setPublic(false);
                        $this->em->persist($current);
                        $this->em->flush();
                    }
                }
            }
        } else if(get_class($event->getSubject()) == $this->workflowClass) { //if it is a 'save' of the workflow entity check the workflow-status of the belonging types and save the formNodes to the real nodes
            //check if there are elements of the type with workflow-status null
            $repository = $this->em->getRepository('Enhavo'.ucfirst($event->getSubject()->getEntity()).'Bundle:'.ucfirst($event->getSubject()->getEntity()));
            $resources = $repository->getEmptyWorkflowStatus();

            if($resources != null) {

                //there are elements of the type with workflow-status null

                //get all nodes of the current workflow
                $nodes = $this->em->getRepository('EnhavoWorkflowBundle:Node')->findBy(array(
                    'workflow' => $event->getSubject()
                ));

                //get start and end node
                $startNode = null;
                $endNode = null;
                foreach ($nodes as $node) {
                    if($node->getStart() == true) {
                        $startNode = $node;
                    } else if($node->getEnd() == true) {
                        $endNode = $node;
                    }
                }

                foreach($resources as $element) {

                    //check if there is a public field in the type
                    if(property_exists($element, 'public')){

                        // if there is a public field, check if it is set true and set the end-node to the workflow-status; if it is false set the start-node to the workflow-status
                        if($element->getPublic() == true){
                            $workflowStatus = new WorkflowStatus();
                            $workflowStatus->setNode($endNode);
                            $this->em->persist($workflowStatus);
                            $this->em->flush();
                            $element->setWorkflowStatus($workflowStatus);
                            $this->em->persist($element);
                            $this->em->flush();
                        } else {
                            $workflowStatus = new WorkflowStatus();
                            $workflowStatus->setNode($startNode);
                            $this->em->persist($workflowStatus);
                            $this->em->flush();
                            $element->setWorkflowStatus($workflowStatus);
                            $this->em->persist($element);
                            $this->em->flush();
                        }
                    } else {

                        //if there is no public field, just set all entries to the start-node
                        $workflowStatus = new WorkflowStatus();
                        $workflowStatus->setNode($startNode);
                        $this->em->persist($workflowStatus);
                        $this->em->flush();
                        $element->setWorkflowStatus($workflowStatus);
                        $this->em->persist($element);
                        $this->em->flush();
                    }
                }
            }

            //write displayed FormNodes into the real nodes
            $formNodes = $event->getSubject()->getFormNodes();
            $lastFormNode = array_pop($formNodes); //the last formNode is an array and keeps all the important information
            $newNodesCounter = 0;
            if (is_array($lastFormNode)) {

                //get all the old nodes
                $oldNodes = array();
                foreach($event->getSubject()->getNodes() as $oldNode){
                    if($oldNode->getStart() != true){
                        $oldNodes[] = $oldNode;
                    }
                }

                //check if there are differences between the formNodes and the old nodes
                if($lastFormNode != $oldNodes) {

                    //there are differences

                    //get the belonging workflow
                    $workflowRepository = $this->em->getRepository('EnhavoWorkflowBundle:Workflow');
                    $currentWF = $workflowRepository->find($event->getSubject()->getId());
                    $transitions = $this->em->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                        'workflow' => $currentWF
                    ));

                    //write the now information from the formNodes to the real nodes
                    foreach ($currentWF->getNodes() as $node) {
                        if($node->getStart() == true){
                            continue;
                        } else {
                            if(array_key_exists($newNodesCounter, $lastFormNode)) {
                                if ($node == $lastFormNode[$newNodesCounter]) {
                                    $newNodesCounter++;
                                    continue;
                                } else {
                                    $currentWF->removeNode($node);
                                    $currentWF->addNode($lastFormNode[$newNodesCounter]);
                                    foreach($transitions as $transition) {
                                        if($transition->getNodeFrom() == $node || $transition->getNodeTo() == $node) {
                                            $this->em->remove($transition);
                                            $this->em->flush();
                                        }
                                    }
                                    $this->em->persist($currentWF);
                                    $this->em->remove($node);
                                    $this->em->flush();
                                    $newNodesCounter++;
                                }
                            } else {
                                $currentWF->removeNode($node);
                                foreach($transitions as $transition) {
                                    if($transition->getNodeFrom() == $node || $transition->getNodeTo() == $node) {
                                        $this->em->remove($transition);
                                        $this->em->flush();
                                    }
                                }
                                $this->em->persist($currentWF);
                                $this->em->remove($node);
                                $this->em->flush();
                                $newNodesCounter++;
                            }
                        }
                    }
                    for ($i = $newNodesCounter; $i < count($lastFormNode); $i++) {
                        $currentWF->addNode($lastFormNode[$i]);
                        $this->em->persist($currentWF);
                        $this->em->flush();
                    }
                }
            }
        }
    }
}