<?php

namespace Enhavo\Bundle\WorkflowBundle\Entity;
use Enhavo\Bundle\WorkflowBundle\Model\NodeInterface;
use Enhavo\Bundle\WorkflowBundle\Model\TransitionInterface;
use Enhavo\Bundle\WorkflowBundle\Model\WorkflowInterface;

/**
 * Node
 */
class Node implements NodeInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $node_name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $transitions;

    /**
     * @var WorkflowInterface
     */
    private $workflow;

    /**
     * @var boolean
     */
    private $end;

    /**
     * @var boolean
     */
    private $start;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transitions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nodeName
     *
     * @param string $nodeName
     *
     * @return Node
     */
    public function setNodeName($nodeName)
    {
        $this->node_name = $nodeName;

        return $this;
    }

    /**
     * Get nodeName
     *
     * @return string
     */
    public function getNodeName()
    {
        return $this->node_name;
    }

    /**
     * Add transition
     *
     * @param TransitionInterface $transition
     *
     * @return Node
     */
    public function addTransition(TransitionInterface $transition)
    {
        $this->transitions[] = $transition;

        return $this;
    }

    /**
     * Remove transition
     *
     * @param TransitionInterface $transition
     */
    public function removeTransition(TransitionInterface $transition)
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
     * Set workflow
     *
     * @param WorkflowInterface $workflow
     *
     * @return Node
     */
    public function setWorkflow(WorkflowInterface $workflow = null)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * Get workflow
     *
     * @return WorkflowInterface
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Set end
     *
     * @param boolean $end
     *
     * @return Node
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return boolean
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set start
     *
     * @param boolean $start
     *
     * @return Node
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return boolean
     */
    public function getStart()
    {
        return $this->start;
    }
}
