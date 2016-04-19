<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus;
use Symfony\Component\EventDispatcher\GenericEvent;

class SaveListener
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onSave(GenericEvent $event)
    {
        $array = explode('\\', get_class($event->getSubject()));
        $entity = array_pop($array);
        //get workflow of entity
        $workflowRepository = $this->em->getRepository('EnhavoWorkflowBundle:Workflow');
        $workflow = $workflowRepository->findOneBy(array(
            'entity' => strtolower($entity)
        ));
        if($workflow != null){
            $currentWorkflowStatus = $event->getSubject()->getWorkflowStatus();
            if($currentWorkflowStatus != null){
                $currentNode = $currentWorkflowStatus->getNode();
                if($currentNode == null) {
                    //set creationNode
                    $nodes = $workflow->getNodes();
                    foreach($nodes as $node) {
                        if($node->getStart() == true) {
                            $currentWorkflowStatus->setNode($node);
                            $currentNode = $currentWorkflowStatus->getNode();
                            break;
                        }
                    }
                }
                if($currentNode->getEnd() == true){
                    $current = $event->getSubject();
                    //if public feld exists dann auf true setzen
                    if(property_exists($current, 'public')){
                        $current->setPublic(true);
                        $this->em->persist($current);
                        $this->em->flush();
                    }
                }
                else {
                    $current = $event->getSubject();
                    //if public feld exists dann auf true setzen
                    if(property_exists($current, 'public')){
                        $current->setPublic(false);
                        $this->em->persist($current);
                        $this->em->flush();
                    }
                }
            }
        } else if($entity == 'Workflow') {
            //check if there are elements with workflowstatus null if the workflow is active
            $repository = $this->em->getRepository('Enhavo'.ucfirst($event->getSubject()->getEntity()).'Bundle:'.ucfirst($event->getSubject()->getEntity()));
            $wfnull = $repository->getWorkflowStatusNull();
            if($wfnull != null) {
                //if there are set the workflow_status
                $nodes = $this->em->getRepository('EnhavoWorkflowBundle:Node')->findBy(array(
                    'workflow' => $event->getSubject()
                ));
                $startNode = null;
                $endNode = null;
                foreach ($nodes as $node) {
                    if($node->getStart() == true) {
                        $startNode = $node;
                    } else if($node->getEnd() == true) {
                        $endNode = $node;
                    }
                }

                foreach($wfnull as $element) {
                    if(property_exists($element, 'public')){
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
            //write displayed FormNodes in real nodes
            $formNodes = $event->getSubject()->getFormNodes();
            $lastFormNode = array_pop($formNodes);
            $newNodesCounter = 0;
            if (is_array($lastFormNode)) {
                $oldNodes = array();
                foreach($event->getSubject()->getNodes() as $oldNode){
                    if($oldNode->getStart() != true){
                        $oldNodes[] = $oldNode;
                    }
                }
                if($lastFormNode != $oldNodes) {
                    $workflowRepository = $this->em->getRepository('EnhavoWorkflowBundle:Workflow');
                    $currentWF = $workflowRepository->find($event->getSubject()->getId());
                    $transitions = $this->em->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
                        'workflow' => $currentWF
                    ));
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