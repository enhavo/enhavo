<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;

class DeleteListener
{
    protected $em;

    protected $workflowClass;

    protected $container;

    public function __construct(EntityManager $em, $workflowClass, $container)
    {
        $this->em = $em;
        $this->workflowClass = $workflowClass;
        $this->container = $container;
    }

    //you need to delete all rows which belong to the workflow before you can delete the workflow itself (transitions->nodes->workflow-status)
    public function onDelete(GenericEvent $event)
    {
        if(get_class($event->getSubject()) == $this->workflowClass){

            //get the current workflow
            $workflowRepository = $this->em->getRepository('EnhavoWorkflowBundle:Workflow');
            $workflow = $workflowRepository->find($event->getSubject()->getId());

            $this->removeTransitions($workflow);
            $this->removeNodesAndWorkflowStatus($workflow);

            $this->em->flush();
        }
    }

    protected function removeTransitions($workflow)
    {
        //get the transitions of the current workflow
        $transitionRepository = $this->em->getRepository('EnhavoWorkflowBundle:Transition');
        $transitions = $transitionRepository->findBy(array(
            'workflow' => $workflow
        ));

        //remove these transitions
        foreach($transitions as $transition) {
            $this->em->remove($transition);
        }
    }

    protected function removeNodesAndWorkflowStatus($workflow)
    {
        //get the nodes of the current workflow
        $nodeRepository = $this->em->getRepository('EnhavoWorkflowBundle:Node');
        $nodes = $nodeRepository->findBy(array(
            'workflow' => $workflow
        ));

        //find the current entity repository
        $possibleWFEntities = $this->container->getParameter('enhavo_workflow.entities');
        $currentEntityRepository = null;
        foreach($possibleWFEntities as $possibleEntity){
            if($possibleEntity['class'] == $workflow->getEntity()) {
                $currentEntityRepository = $possibleEntity['repository'];
                break;
            }
        }
        $repository = null;
        if(strpos($currentEntityRepository, ':')){
            $repository = $this->em->getRepository($currentEntityRepository);
        } else {
            $repository = $this->container->get($currentEntityRepository);
        }

        //get workflow-status which belongs to the current workflow
        $allWorkflowStatus = $this->em->getRepository('EnhavoWorkflowBundle:WorkflowStatus')->findAll();
        foreach($allWorkflowStatus as $workflowStatus) {
            foreach($nodes as $node) {
                if($workflowStatus->getNode() == $node){
                    //get types with workflow-status

                    $currentObject = $repository->findOneBy(array(
                        'workflow_status' => $workflowStatus
                    ));
                    if($currentObject != null) {
                        //set workflow-status null in types
                        $currentObject->setWorkflowStatus(null);
                    }
                    //remove workflow-status
                    $this->em->remove($workflowStatus);
                }
            }
        }
        //remove these nodes
        foreach($nodes as $node) {
            $this->em->remove($node);
        }
    }
}