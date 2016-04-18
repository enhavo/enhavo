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

    private $formNodes;

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
        if(count($this->getNodes()) == 0) {
            $creationNode = new Node();
            $creationNode->setNodeName('creation');
            $creationNode->setEnd(false);
            $creationNode->setWorkflow($this);
            $this->nodes[] = $creationNode;
        }
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
        $transition->setWorkflow(null);
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
     * @var string
     */
    private $entity;


    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return Workflow
     */
    public function setEntity($entity)
    {
        if($entity == null) {
            $entity = $this->getEntity();
        }
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }
    /**
     * @var boolean
     */
    private $active;


    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Workflow
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    public function setFormNodes($formNode)
    {
        $this->formNodes[] = $formNode;
        return $this;
    }

    public function getFormNodes()
    {
        return $this->formNodes;
    }
}
