<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
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
                        if($node->getNodeName() == 'creation') {
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
        }
    }
}