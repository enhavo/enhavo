<?php

namespace Enhavo\Bundle\WorkflowBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class WorkflowRepository extends EntityRepository
{
    public function findFormNodes($id)
    {
        //get the current workflow with the given ID
        $wf = $this->find($id);

        //get nodes
        $nodes = $wf->getNodes();

        //set all nodes but the start-node to the formNodes
        foreach ($nodes as $node) {
            if($node->getStart() != true){
                $wf->setFormNodes($node);
            }
        }
        return $wf;
    }
}