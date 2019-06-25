<?php

/**
 * BlockTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\FormBundle\DynamicForm\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Enhavo\Bundle\BlockBundle\Entity\Node;

class NodeFactory implements FactoryInterface
{
    use ContainerAwareTrait;

    /**
     * @var BlockFactory
     */
    private $blockFactory;

    public function __construct(BlockFactory $blockFactory)
    {
        $this->blockFactory = $blockFactory;
    }

    public function createNew()
    {
        $node = new Node();
        return $node;
    }

    public function duplicate(NodeInterface $original)
    {
        $node = new Node();
        $node->setName($original->getName());
        $node->setPosition($original->getPosition());
        $node->setEnable($original->isEnable());
        $node->setBlock($this->blockFactory->duplicate($original->getBlock()));
        return $node;
    }
}
