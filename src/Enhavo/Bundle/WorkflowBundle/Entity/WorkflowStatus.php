<?php

namespace Enhavo\Bundle\WorkflowBundle\Entity;
use Enhavo\Bundle\WorkflowBundle\Model\NodeInterface;
use Enhavo\Bundle\WorkflowBundle\Model\WorkflowStatusInterface;

/**
 * WorkflowStatus
 */
class WorkflowStatus implements WorkflowStatusInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var NodeInterface
     */
    private $node;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set node
     *
     * @param NodeInterface $node
     *
     * @return WorkflowStatus
     */
    public function setNode(NodeInterface $node = null)
    {
        if($node != null){
            $this->node = $node;

            return $this;
        }
    }

    /**
     * Get node
     *
     * @return NodeInterface
     */
    public function getNode()
    {
        return $this->node;
    }

}
