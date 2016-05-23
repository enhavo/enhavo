<?php

namespace Enhavo\Bundle\WorkflowBundle\Entity;

use Enhavo\Bundle\UserBundle\Entity\Group;
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
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $nodeFrom;

    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Node
     */
    private $nodeTo;

    /**
     * @var \Enhavo\Bundle\WorkflowBundle\Entity\Workflow
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
     * Get nodeNameTo
     *
     * @return string
     */
    public function getNodeNameTo()
    {
        return $this->nodeTo->getName();
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

    /**
     * Add group
     *
     * @param \Enhavo\Bundle\UserBundle\Entity\Group $group
     *
     * @return Transition
     */
    public function addGroup(\Enhavo\Bundle\UserBundle\Entity\Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \Enhavo\Bundle\UserBundle\Entity\Group $group
     */
    public function removeGroup(\Enhavo\Bundle\UserBundle\Entity\Group $group)
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
