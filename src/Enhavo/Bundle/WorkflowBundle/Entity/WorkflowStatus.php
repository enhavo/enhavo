<?php

namespace Enhavo\Bundle\WorkflowBundle\Entity;

/**
 * WorkflowStatus
 */
class WorkflowStatus
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
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
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Node $node
     *
     * @return WorkflowStatus
     */
    public function setNode(\Enhavo\Bundle\WorkflowBundle\Entity\Node $node = null)
    {
        if($node != null){
            $this->node = $node;

            return $this;
        }
    }

    /**
     * Get node
     *
     * @return \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    public function getNode()
    {
        return $this->node;
    }

}
