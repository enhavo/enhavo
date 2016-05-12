<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 12.05.16
 * Time: 10:25
 */

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus;
use Symfony\Component\EventDispatcher\GenericEvent;

class WorkflowSaveListener {

    protected $em;

    protected $container;

    protected $workflowClass;

    public function __construct(EntityManager $em, $workflowClass, $container)
    {
        $this->em = $em;
        $this->workflowClass = $workflowClass;
        $this->container = $container;
    }

    public function onSave(GenericEvent $event)
    {
       if(get_class($event->getSubject()) == $this->workflowClass) { //if it is a 'save' of the workflow entity check the workflow-status of the belonging types and save the formNodes to the real nodes

            //check if there are elements of the type with workflow-status null

            //get the repository for the changed workflow
            $possibleWFEntities = $this->container->getParameter('enhavo_workflow.entities');
            $currentRepository = null;
            foreach($possibleWFEntities as $possibleEntity) {
                if($possibleEntity['class'] == $event->getSubject()->getEntity()){
                    $currentRepository = $possibleEntity['repository'];
                    break;
                }
            }
            $repository = null;
            if(strpos($currentRepository, ':')){
                $repository = $this->em->getRepository($currentRepository);
            } else {
                $repository = $this->container->get($currentRepository);

            }

            //get all entires of the workflows entity with workflow-status null
            $resources = $repository->getEmptyWorkflowStatus();

            //check if there are some entries with workflow-status null
            if($resources != null) {

                //set a workflow-status if the current workflow is active
                if($event->getSubject()->getActive()){
                    $this->setWorkflowStatus($event, $resources);
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
                        if($node->getStart()){
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

    protected function setWorkflowStatus($event, $resources)
    {
        //get all nodes of the current workflow
        $nodes = $this->em->getRepository('EnhavoWorkflowBundle:Node')->findBy(array(
            'workflow' => $event->getSubject()
        ));

        //get start and end node
        $startNode = null;
        $endNode = null;
        foreach ($nodes as $node) {
            if($node->getStart()) {
                $startNode = $node;
            } else if($node->getEnd()) {
                $endNode = $node;
            }
        }

        foreach($resources as $element) {
            $workflowStatus = new WorkflowStatus();

            //check if there is a public field in the type
            if(method_exists($element, 'getPublic')){

                // if there is a public field, check if it is set true and set the end-node to the workflow-status; if it is false set the start-node to the workflow-status
                if($element->getPublic()){
                    $workflowStatus->setNode($endNode);
                } else {
                    $workflowStatus->setNode($startNode);
                }
            } else {

                //if there is no public field, just set all entries to the start-node
                $workflowStatus->setNode($startNode);
            }
            $this->em->persist($workflowStatus);
            $this->em->flush();
            $element->setWorkflowStatus($workflowStatus);
            $this->em->persist($element);
            $this->em->flush();
        }
    }
}