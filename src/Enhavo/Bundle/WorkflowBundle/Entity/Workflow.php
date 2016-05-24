<?php

namespace Enhavo\Bundle\WorkflowBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\WorkflowBundle\Model\NodeInterface;
use Enhavo\Bundle\WorkflowBundle\Model\TransitionInterface;
use Enhavo\Bundle\WorkflowBundle\Model\WorkflowInterface;

/**
 * Workflow
 */
class Workflow implements WorkflowInterface
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $nodes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $transitions;

    private $formNodes;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var boolean
     */
    private $active;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nodes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formNodes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Workflow
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
     * Add node
     *
     * @param NodeInterface $node
     *
     * @return Workflow
     */
    public function addNode(NodeInterface $node)
    {
        //add a 'creation-node' to every workflow
        if(count($this->getNodes()) == 0) {
            $creationNode = new Node();
            $creationNode->setName('creation');
            $creationNode->setEnd(false);
            $creationNode->setStart(true);
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
     * @param NodeInterface $node
     */
    public function removeNode(NodeInterface $node)
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
     * @param TransitionInterface $transition
     *
     * @return Workflow
     */
    public function addTransition(TransitionInterface $transition)
    {
        $this->transitions[] = $transition;
        $transition->setWorkflow($this);
        return $this;
    }

    /**
     * Remove transition
     *
     * @param TransitionInterface $transition
     */
    public function removeTransition(TransitionInterface $transition)
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


    /**
     * Add form node
     *
     * @param NodeInterface $node
     *
     * @return Workflow
     */
    public function addFormNode(NodeInterface $node)
    {
        $this->formNodes[] = $node;
        return $this;
    }

    /**
     * Remove form node
     *
     * @param NodeInterface $node
     */
    public function removeFormNode(NodeInterface $node)
    {
        if(is_array($this->formNodes)) {
            foreach ($this->formNodes as $key => $formNode) {
                if($formNode == $node){
                    unset($this->formNodes[$key]);
                    return;
                }
            }

        } else {
            $this->formNodes->removeElement($node);
        }
    }

    /**
     * Get form nodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormNodes()
    {
        return $this->formNodes;
    }
}
