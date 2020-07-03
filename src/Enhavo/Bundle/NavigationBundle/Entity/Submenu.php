<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-02
 * Time: 10:38
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;


use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Model\SubjectInterface;

class Submenu implements SubjectInterface
{
    /** @var integer|null */
    private $id;

    /** @var NodeInterface|null */
    private $node;

    /** @var array */
    private $nodes = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|NodeInterface[]
     */
    public function getNodes()
    {
        if($this->node) {
            return $this->node->getChildren();
        }
        return $this->nodes;
    }

    /**
     * @return NodeInterface|null
     */
    public function getNode(): ?NodeInterface
    {
        return $this->node;
    }

    /**
     * @param NodeInterface|null $node
     */
    public function setNode(?NodeInterface $node): void
    {
        $this->node = $node;
        foreach($this->nodes as $node) {
            $this->node->addChild($node);
        }
    }

    /**
     * @param NodeInterface $node
     */
    public function addNode(NodeInterface $node)
    {
        if ($this->node) {
            $this->node->addChild($node);
        }

        $this->nodes[] = $node;
    }

    /**
     * @param NodeInterface $node
     */
    public function removeNode(NodeInterface $node)
    {
        if ($this->node) {
            $this->node->removeChild($node);
        }

        if (false !== $key = array_search($node, $this->nodes, true)) {
            array_splice($this->nodes, $key, 1);
        }
    }
}
