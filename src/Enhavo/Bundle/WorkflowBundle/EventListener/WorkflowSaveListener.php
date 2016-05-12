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

            $workflow = $event->getSubject();

            //get the repository for the changed workflow
            $repository = $this->getEntityRepository($workflow);

            //get all entries with workflow-status null
            $resources = $repository->getEmptyWorkflowStatus();

            //check if there are some entries with workflow-status null
            if($resources != null) {
                //set a workflow-status if the current workflow is active
                if($workflow->getActive()){
                    $this->setWorkflowStatus($workflow, $resources);
                }
            }
            $this->writeNodes($workflow, $repository);

            $this->em->flush();
        }
    }

    protected function writeNodes($workflow, $repository)
    {
        //write displayed FormNodes into the real nodes
        $formNodes = $workflow->getFormNodes();
        $nodesCollection = $workflow->getNodes();
        $realNodes = array();
        foreach ($nodesCollection as $node) {
            $realNodes[] = $node;
        }

        //workflow transitions
        $transitions = $this->em->getRepository('EnhavoWorkflowBundle:Transition')->findBy(array(
            'workflow' => $workflow
        ));

        //get all workflow-status in case a node got removed
        $allWFS = $this->em->getRepository('EnhavoWorkflowBundle:WorkflowStatus')->findAll();

        //remove nodes (if some nodes have been removed from formNodes)
        foreach($realNodes as $realNode){
            if(!in_array($realNode,$formNodes) && !$realNode->getStart()){
                $workflow->removeNode($realNode);
                foreach($transitions as $transition){
                    if($transition->getNodeFrom() == $realNode || $transition->getNodeTo() == $realNode) {
                        $this->em->remove($transition);
                    }
                }
                //check if the removed node had a wfs
                foreach($allWFS as $wfs){
                    if($wfs->getNode() == $realNode){
                        $currentResource = $repository->findOneBy(array(
                            'workflow_status' => $wfs
                        ));
                        $currentResource->setWorkflowStatus(null);
                        $this->em->remove($wfs);
                    }
                }
                $this->em->remove($realNode);
            }
        }

        //create realNodes
        foreach ($formNodes as $key => $formNode) {
            if(!in_array($formNode,$realNodes) ){
                $workflow->addNode($formNode);
            }
        }
    }

    protected function setWorkflowStatus($workflow, $resources)
    {
        //get all nodes of the current workflow
        $nodes = $this->em->getRepository('EnhavoWorkflowBundle:Node')->findBy(array(
            'workflow' => $workflow
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
            $element->setWorkflowStatus($workflowStatus);
        }
    }

    protected function getEntityRepository($workflow)
    {
        $possibleWFEntities = $this->container->getParameter('enhavo_workflow.entities');
        $currentRepository = null;
        foreach($possibleWFEntities as $possibleEntity) {
            if($possibleEntity['class'] == $workflow->getEntity()){
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
        return $repository;
    }
}