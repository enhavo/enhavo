<?php

namespace Enhavo\Bundle\WorkflowBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;

class UpdateListener
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onPreUpdate(GenericEvent $event)
    {
        $array = explode('\\', get_class($event->getSubject()));
        $entity = array_pop($array);

        /*if($entity == 'Workflow') {
            $formNodes = $event->getSubject()->getFormNodes();
            $lastFormNode = array_pop($formNodes);
            if (is_array($lastFormNode)) {
                $newNodesCounter = 0;
                foreach ($event->getSubject()->getNodes() as $node) {
                    if ($node->getNodeName() == 'creation') {
                        continue;
                    } else {
                        if(array_key_exists($newNodesCounter, $lastFormNode)) {
                            if ($node == $lastFormNode[$newNodesCounter]) {
                                $newNodesCounter++;
                                continue;
                            } else {
                                $event->getSubject()->removeNode($node);
                                $event->getSubject()->addNode($lastFormNode[$newNodesCounter]);
                                $newNodesCounter++;
                            }
                        } else {
                            $event->getSubject()->removeNode($node);
                            $newNodesCounter++;
                        }
                    }
                }
                for ($i = $newNodesCounter; $i < count($lastFormNode); $i++) {
                    $event->getSubject()->addNode($lastFormNode[$i]);
                }
            }
        }*/
    }
}