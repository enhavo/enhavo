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

    public function hasActiveWorkflow($resource)
    {
        $array = explode('\\', get_class($resource));
        $entity = array_pop($array);

        //get the current workflow of the clicked element
        $workflow = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->findOneBy(array(
            'entity' => $entity,
        ));
    }
}