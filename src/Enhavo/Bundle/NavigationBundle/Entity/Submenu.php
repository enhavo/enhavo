<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Model\SubjectInterface;

class Submenu implements SubjectInterface
{
    /** @var int|null */
    private $id;

    /** @var NodeInterface|null */
    private $node;

    /** @var array */
    private $nodes = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|NodeInterface[]
     */
    public function getNodes()
    {
        if ($this->node) {
            return $this->node->getChildren();
        }

        return $this->nodes;
    }

    public function getNode(): ?NodeInterface
    {
        return $this->node;
    }

    public function setNode(?NodeInterface $node): void
    {
        $this->node = $node;
        foreach ($this->nodes as $node) {
            $this->node->addChild($node);
        }
    }

    public function addNode(NodeInterface $node)
    {
        if ($this->node) {
            $this->node->addChild($node);
        }

        $this->nodes[] = $node;
    }

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
