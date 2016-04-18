<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\EventDispatcher\GenericEvent;

class DeleteListener
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onDelete(GenericEvent $event)
    {
        $array = explode('\\', get_class($event->getSubject()));
        $entity = array_pop($array);
        if($entity == 'Workflow'){
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
            $allWorkflowStatus = $this->em->getRepository('EnhavoWorkflowBundle:WorkflowStatus')->findAll();
            foreach($allWorkflowStatus as $workflowStatus) {
                foreach($nodes as $node) {
                    if($workflowStatus->getNode() == $node){
                        $currentObject = $this->em->getRepository('Enhavo'.ucfirst($workflow->getEntity()).'Bundle:'.ucfirst($workflow->getEntity()))->findOneBy(array(
                            'workflow_status' => $workflowStatus
                        ));
                        if($currentObject != null) {
                            $currentObject->setWorkflowStatus(null);
                        }
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