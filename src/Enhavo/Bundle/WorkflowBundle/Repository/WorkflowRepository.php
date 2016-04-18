<?php

namespace Enhavo\Bundle\WorkflowBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class WorkflowRepository extends EntityRepository
{
    public function findFormNodes($id)
    {
        //Workflow mit Id holen
        $wf = $this->find($id);

        //Nodes vom WF holen
        $nodes = $wf->getNodes();

        //alle Nodes außer creation in FormNodes speichern
        foreach ($nodes as $node) {
            if($node->getNodeName() != 'creation'){
                $wf->setFormNodes($node);
            }
        }
        return $wf;
    }
}