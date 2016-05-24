<?php

namespace Enhavo\Bundle\WorkflowBundle\Entity;

use Enhavo\Bundle\WorkflowBundle\Model\NodeInterface;
use Enhavo\Bundle\WorkflowBundle\Model\TransitionInterface;
use Enhavo\Bundle\WorkflowBundle\Model\WorkflowInterface;
use FOS\UserBundle\Model\GroupInterface;

/**
 * Transition
 */
class Transition implements TransitionInterface
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
     * @var NodeInterface
     */
    private $node_from;

    /**
     * @var NodeInterface
     */
    private $node_to;

    /**
     * @var WorkflowInterface
     */
    private $workflow;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $groups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param NodeInterface $nodeFrom
     *
     * @return Transition
     */
    public function setNodeFrom(NodeInterface $nodeFrom = null)
    {
        $this->node_from = $nodeFrom;

        return $this;
    }

    /**
     * Get nodeFrom
     *
     * @return NodeInterface
     */
    public function getNodeFrom()
    {
        return $this->node_from;
    }

    /**
     * Set nodeTo
     *
     * @param NodeInterface $nodeTo
     *
     * @return Transition
     */
    public function setNodeTo(NodeInterface $nodeTo = null)
    {
        $this->node_to = $nodeTo;

        return $this;
    }

    /**
     * Get nodeTo
     *
     * @return NodeInterface
     */
    public function getNodeTo()
    {
        return $this->node_to;
    }

    /**
     * Get nodeNameTo
     *
     * @return string
     */
    public function getNodeNameTo()
    {
        return $this->node_to->getNodeName();
    }

    /**
     * Set workflow
     *
     * @param WorkflowInterface $workflow
     *
     * @return Transition
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
     * Add group
     *
     * @param GroupInterface $group
     *
     * @return Transition
     */
    public function addGroup(GroupInterface $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param GroupInterface $group
     */
    public function removeGroup(GroupInterface $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
