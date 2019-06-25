<?php

/**
 * BlockTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\FormBundle\DynamicForm\ResolverInterface;
use Enhavo\Bundle\FormBundle\DynamicForm\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class BlockFactory
{
    use ContainerAwareTrait;

    /**
     * @param string $name
     * @return BlockInterface
     */
    public function createNew($name)
    {
        /** @var Block $block */
        $block = $this->getResolver()->resolveItem($name);
        $factory = $this->getFactory($block->getName());
        return $factory->createNew();
    }

    /**
     * @param BlockInterface $original
     * @return BlockInterface
     */
    public function duplicate(BlockInterface $original)
    {
        /** @var Block $block */
        $block = $this->getResolver()->resolveItem($original->getNode()->getName());
        $factory = $this->getFactory($block->getName());
        return $factory->duplicate($original);
    }

    /**
     * @return ResolverInterface
     */
    private function getResolver()
    {
        return $this->container->get('enhavo_block.resolver.block_resolver');
    }

    /**
     * @param string $name
     * @return FactoryInterface
     */
    private function getFactory($name)
    {
        $blockManager = $this->container->get('enhavo_block.block.manager');
        return $blockManager->getFactory($name);
    }
}
