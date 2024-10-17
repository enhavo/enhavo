<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.08.18
 * Time: 21:56
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\AppBundle\Output\OutputLoggerInterface;
use Enhavo\Bundle\BlockBundle\Factory\AbstractBlockFactory;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class BlockManager
{
    use ContainerAwareTrait;

    /**
     * @var AssociationFinder
     */
    private $associationFinder;

    /**
     * @var Cleaner
     */
    private $cleaner;

    /**
     * @var Block[]
     */
    private $blocks = [];

    public function __construct(FactoryInterface $factory, AssociationFinder $associationFinder, Cleaner $cleaner, $configurations)
    {
        $this->associationFinder = $associationFinder;
        $this->cleaner = $cleaner;

        foreach($configurations as $name => $options) {
            $this->blocks[$name] = $factory->create($options);
        }
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    public function getBlock($name)
    {
        return $this->blocks[$name];
    }

    public function createViewData(NodeInterface $node, $resource = null)
    {
        /** @var BlockManager $manager */
        $manager = $this;
        $this->walk($node, function (NodeInterface $node) use ($manager, $resource) {
            if ($node->getType() === NodeInterface::TYPE_BLOCK) {
                $node->setResource($resource);
                $viewData = $manager->getBlock($node->getName())->createViewData($node->getBlock(), $resource);
                $node->setViewData($viewData);
            }
        });

        $this->walk($node, function (NodeInterface $node) use ($manager, $resource) {
            if ($node->getType() === NodeInterface::TYPE_BLOCK) {
                $viewData = $manager->getBlock($node->getName())->finishViewData($node->getBlock(), $node->getViewData(), $resource);
                $node->setViewData($viewData);
            }
        });
    }

    private function walk(NodeInterface $node, $callback)
    {
        $callback($node);
        foreach($node->getChildren() as $child) {
            $this->walk($child, $callback);
        }
    }

    public function getFactory($name)
    {
        $block = $this->getBlock($name);
        $factoryClass = $block->getFactory();
        if($factoryClass) {
            if ($this->container->has($factoryClass)) {
                $factory = $this->container->get($factoryClass);
            } else {
                /** @var AbstractBlockFactory $factory */
                $factory = new $factoryClass($block->getModel());
                if($factory instanceof ContainerAwareInterface) {
                    $factory->setContainer($this->container);
                }
            }
            return $factory;
        }
        return null;
    }

    /**
     * Find the resource that references the block tree that contains the given block
     *
     * @param BlockInterface $block
     * @return object|null
     */
    public function findRootResource(BlockInterface $block)
    {
        if (!$block->getNode()) {
            return null;
        }
        $rootNode = $block->getNode()->getRoot();
        if (!$rootNode) {
            return null;
        }
        $references = $this->associationFinder->findAssociationsTo($rootNode, NodeInterface::class, [NodeInterface::class, BlockInterface::class]);
        if (count($references) > 0) {
            return $references[0];
        }
        return null;
    }

    /**
     * Search database for orphaned or erroneous nodes and blocks and delete them.
     *
     * @param OutputLoggerInterface? $outputLogger
     * @param bool $dryRun
     * @throws \Exception
     */
    public function cleanUp($outputLogger = null, $dryRun = false)
    {
        $this->cleaner->clean($outputLogger, $dryRun);
    }
}
