<?php

/**
 * BlockTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Factory;

use Enhavo\Bundle\AppBundle\Exception\ResolverException;
use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\FormBundle\DynamicForm\ResolverInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class BlockFactory
{
    use ContainerAwareTrait;

    /**
     * @return BlockInterface
     */
    public function createNew($name)
    {
        /** @var Block $block */
        $block = $this->getResolver()->resolveItem($name);
        $factory = $this->getFactory($block);
        return $factory->createNew();
    }

    /**
     * @param BlockInterface $blockType
     * @return BlockInterface
     */
    public function duplicate(BlockInterface $blockType, $name)
    {
        /** @var Block $block */
        $block = $this->getResolver()->resolveItem($name);
        $factory = $this->getFactory($block);
        return $factory->duplicate($blockType);
    }

    /**
     * @return ResolverInterface
     */
    private function getResolver()
    {
        return $this->container->get('enhavo_block.resolver.block_resolver');
    }

    /**
     * @param Block $block
     * @return FactoryInterface
     * @throws ResolverException
     */
    private function getFactory(Block $block)
    {
        $factoryClass = $block->getFactory();
        if($factoryClass) {
            if ($this->container->has($factoryClass)) {
                $factory = $this->container->get($factoryClass);
            } else {
                /** @var AbstractBlockFactory $factory */
                $factory = new $factoryClass($block->getModel());
                $factory->setContainer($this->container);
            }

            return $factory;
        }
        throw new ResolverException(sprintf('Factory for container block type "%s" is required', $block->getName()));
    }
}
