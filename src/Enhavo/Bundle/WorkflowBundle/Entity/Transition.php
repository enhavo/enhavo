<?php

namespace Enhavo\Bundle\WorkflowBundle\Entity;

/**
 * Transition
 */
class Transition
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $transition_name;

    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $node_from;

    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $node_to;

    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Workflow
     */
    private $workflow;


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
     * Set transitionName
     *
     * @param string $transitionName
     *
     * @return Transition
     */
    public function setTransitionName($transitionName)
    {
        $this->transition_name = $transitionName;

        return $this;
    }

    /**
     * Get transitionName
     *
     * @return string
     */
    public function getTransitionName()
    {
        return $this->transition_name;
    }

    /**
     * Set nodeFrom
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Node $nodeFrom
     *
     * @return Transition
     */
    public function setNodeFrom(\Enhavo\Bundle\WorkflowBundle\Entity\Node $nodeFrom = null)
    {
        $this->node_from = $nodeFrom;

        return $this;
    }

    /**
     * Get nodeFrom
     *
     * @return \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    public function getNodeFrom()
    {
        return $this->node_from;
    }

    /**
     * Set nodeTo
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Node $nodeTo
     *
     * @return Transition
     */
    public function setNodeTo(\Enhavo\Bundle\WorkflowBundle\Entity\Node $nodeTo = null)
    {
        $this->node_to = $nodeTo;

        return $this;
    }

    /**
     * Get nodeTo
     *
     * @return \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    public function getNodeTo()
    {
        return $this->node_to;
    }

    /**
     * Set workflow
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Workflow $workflow
     *
     * @return Transition
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
