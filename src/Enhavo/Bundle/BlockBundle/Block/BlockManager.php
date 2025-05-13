<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\AppBundle\Output\OutputLoggerInterface;
use Enhavo\Bundle\BlockBundle\Factory\BlockFactory;
use Enhavo\Bundle\BlockBundle\Factory\BlockFactoryInterface;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Enhavo\Component\Type\FactoryInterface;
use Psr\Container\ContainerInterface;

class BlockManager
{
    protected ?ContainerInterface $container = null;

    /** @var Block[] */
    private array $blocks = [];

    public function __construct(
        FactoryInterface $factory,
        private readonly AssociationFinder $associationFinder,
        private readonly Cleaner $cleaner,
        $configurations,
    ) {
        foreach ($configurations as $name => $options) {
            $this->blocks[$name] = $factory->create($options);
        }
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }

    public function getBlock($name): Block
    {
        return $this->blocks[$name];
    }

    public function createViewData(NodeInterface $node, $resource = null): void
    {
        $this->walk($node, function (NodeInterface $node) use ($resource): void {
            if (NodeInterface::TYPE_BLOCK === $node->getType()) {
                $node->setResource($resource);
                $viewData = $node->getBlock() ? $this->getBlock($node->getName())->createViewData($node->getBlock(), $resource) : [];
                $node->setViewData($viewData);
            }
        });

        $this->walk($node, function (NodeInterface $node) use ($resource): void {
            if (NodeInterface::TYPE_BLOCK === $node->getType()) {
                $viewData = $node->getBlock() ? $this->getBlock($node->getName())->finishViewData($node->getBlock(), $node->getViewData(), $resource) : [];
                $node->setViewData($viewData);
            }
        });
    }

    private function walk(NodeInterface $node, $callback): void
    {
        $callback($node);
        foreach ($node->getChildren() as $child) {
            $this->walk($child, $callback);
        }
    }

    public function getFactory($name): BlockFactoryInterface
    {
        $block = $this->getBlock($name);
        $factoryClass = $block->getFactory();
        if ($factoryClass) {
            if ($this->container->has($factoryClass)) {
                $factory = $this->container->get($factoryClass);

                return $factory;
            }
        }

        return new BlockFactory($block->getModel());
    }

    /**
     * Find the resource that references the block tree that contains the given block
     */
    public function findRootResource(BlockInterface $block): ?object
    {
        if (!$block->getNode()) {
            return null;
        }

        $rootNode = $block->getNode()->getRoot();
        if (!$rootNode) {
            return null;
        }

        if ($rootNode->getResource()) {
            return $rootNode->getResource();
        }

        if ($rootNode->getId()) {
            $references = $this->associationFinder->findAssociationsTo($rootNode, NodeInterface::class, [NodeInterface::class, BlockInterface::class]);
            if (count($references) > 0) {
                return $references[0];
            }
        }

        return null;
    }

    /**
     * Search database for orphaned or erroneous nodes and blocks and delete them.
     */
    public function cleanUp(?OutputLoggerInterface $outputLogger = null, bool $dryRun = false): void
    {
        $this->cleaner->clean($outputLogger, $dryRun);
    }
}
