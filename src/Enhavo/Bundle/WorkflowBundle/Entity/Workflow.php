<?php

namespace Enhavo\Bundle\WorkflowBundle\Entity;

/**
 * Workflow
 */
class Workflow
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $workflow_name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $nodes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $transitions;

    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $start_node;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nodes = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set workflowName
     *
     * @param string $workflowName
     *
     * @return Workflow
     */
    public function setWorkflowName($workflowName)
    {
        $this->workflow_name = $workflowName;

        return $this;
    }

    /**
     * Get workflowName
     *
     * @return string
     */
    public function getWorkflowName()
    {
        return $this->workflow_name;
    }

    /**
     * Add node
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\node $node
     *
     * @return Workflow
     */
    public function addNode(\Enhavo\Bundle\WorkflowBundle\Entity\node $node)
    {
        $this->nodes[] = $node;
        $node->setWorkflow($this);

        return $this;
    }

    /**
     * Remove node
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\node $node
     */
    public function removeNode(\Enhavo\Bundle\WorkflowBundle\Entity\node $node)
    {
        $this->nodes->removeElement($node);
    }

    /**
     * Get nodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * Add transition
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Transition $transition
     *
     * @return Workflow
     */
    public function addTransition(\Enhavo\Bundle\WorkflowBundle\Entity\Transition $transition)
    {
        $this->transitions[] = $transition;
        $transition->setWorkflow($this);
        return $this;
    }

    /**
     * Remove transition
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Transition $transition
     */
    public function removeTransition(\Enhavo\Bundle\WorkflowBundle\Entity\Transition $transition)
    {
        $this->transitions->removeElement($transition);
    }

    /**
     * Get transitions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     * Set startNode
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Node $startNode
     *
     * @return Workflow
     */
    public function setStartNode(\Enhavo\Bundle\WorkflowBundle\Entity\Node $startNode = null)
    {
        $this->start_node = $startNode;

        return $this;
    }

    /**
     * Get startNode
     *
     * @return \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    public function getStartNode()
    {
        return $this->start_node;
    }
}
