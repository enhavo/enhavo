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
    private $name;


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
     * Set name
     *
     * @param string $name
     *
     * @return Transition
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $node;


    /**
     * Set node
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Node $node
     *
     * @return Transition
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
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $nodeFrom;


    /**
     * Set nodeFrom
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Node $nodeFrom
     *
     * @return Transition
     */
    public function setNodeFrom(\Enhavo\Bundle\WorkflowBundle\Entity\Node $nodeFrom = null)
    {
        $this->nodeFrom = $nodeFrom;

        return $this;
    }

    /**
     * Get nodeFrom
     *
     * @return \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    public function getNodeFrom()
    {
        return $this->nodeFrom;
    }
    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $nodeTo;


    /**
     * Set nodeTo
     *
     * @param \Enhavo\Bundle\WorkflowBundle\Entity\Node $nodeTo
     *
     * @return Transition
     */
    public function setNodeTo(\Enhavo\Bundle\WorkflowBundle\Entity\Node $nodeTo = null)
    {
        $this->nodeTo = $nodeTo;

        return $this;
    }

    /**
     * Get nodeTo
     *
     * @return \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    public function getNodeTo()
    {
        return $this->nodeTo;
    }
    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $node_from;

    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $node_to;


    /**
     * @var string
     */
    private $transition_name;


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
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Workflow
     */
    private $workflow;


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
