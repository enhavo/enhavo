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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var integer
     */
    private $reference;

    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $node;

    /**
     * Set reference
     *
     * @param integer $reference
     *
     * @return WorkflowStatus
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return integer
     */
    public function getReference()
    {
        return $this->reference;
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
        $this->node = $node;

        return $this;
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
    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Workflow
     */
    private $workflow;


    /**
     * Set workflow
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Workflow $workflow
     *
     * @return WorkflowStatus
     */
    public function setWorkflow(\Enhavo\Bundle\WorkflowBundle\Entity\Workflow $workflow = null)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * Get workflow
     *
     * @return \Enhavo\Bundle\WorkflowBundle\Entity\Workflow
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }
}
