<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;

class DeleteListener
{
    protected $em;

    protected $workflowClass;

    public function __construct(EntityManager $em, $workflowClass)
    {
        $this->em = $em;
        $this->workflowClass = $workflowClass;
    }

    //you need to delete all rows which belong to the workflow before you can delete the workflow itself (transitions->nodes->workflow-status)
    public function onDelete(GenericEvent $event)
    {
        if(get_class($event->getSubject()) == $this->workflowClass){

            //get the current workflow
            $workflowRepository = $this->em->getRepository('EnhavoWorkflowBundle:Workflow');
            $workflow = $workflowRepository->find($event->getSubject()->getId());

            //get the transitions of the current workflow
            $transitionRepository = $this->em->getRepository('EnhavoWorkflowBundle:Transition');
            $transitions = $transitionRepository->findBy(array(
                'workflow' => $workflow
            ));

            //remove these transitions
            foreach($transitions as $transition) {
                $this->em->remove($transition);
            }
            $this->em->flush();

            //get the nodes of the current workflow
            $nodeRepository = $this->em->getRepository('EnhavoWorkflowBundle:Node');
            $nodes = $nodeRepository->findBy(array(
                'workflow' => $workflow
            ));

            //get workflow-status which belongs to the current workflow
            $allWorkflowStatus = $this->em->getRepository('EnhavoWorkflowBundle:WorkflowStatus')->findAll();
            foreach($allWorkflowStatus as $workflowStatus) {
                foreach($nodes as $node) {
                    if($workflowStatus->getNode() == $node){
                        //get types with workflow-status
                        $currentObject = $this->em->getRepository('Enhavo'.ucfirst($workflow->getEntity()).'Bundle:'.ucfirst($workflow->getEntity()))->findOneBy(array(
                            'workflow_status' => $workflowStatus
                        ));
                        if($currentObject != null) {
                            //set workflow-status null in types
                            $currentObject->setWorkflowStatus(null);
                        }
                        //remove workflow-status
                        $this->em->remove($workflowStatus);
                        $this->em->flush();
                    }
                }
            }
            //remove these nodes
            foreach($nodes as $node) {
                $this->em->remove($node);
            }
            $this->em->flush();
        }
    }
}