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

    /**
     * @var string
     */
    private $name;

    public function __construct(BlockFactory $blockFactory, $name)
    {
        $this->blockFactory = $blockFactory;
        $this->name = $name;
    }

    public function createNew()
    {
        $node = new Node();
        $node->setBlock($this->blockFactory->createNew($this->name));
        $node->setName($this->name);
        return $node;
    }

    public function duplicate(NodeInterface $original)
    {
        $node = new Node();
        $node->setName($original->getName());
        $node->setPosition($original->getPosition());
        $node->setEnable($original->isEnable());
        $node->setBlock($this->blockFactory->duplicate($original->getBlock(), $original->getName()));
        return $node;
    }
}
