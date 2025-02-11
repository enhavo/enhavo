<?php

namespace Enhavo\Bundle\BlockBundle\Block;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Output\ChainOutputLogger;
use Enhavo\Bundle\AppBundle\Output\OutputLoggerInterface;
use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Monolog\Level;

class Cleaner
{
    private ?BlockManager $blockManager = null;
    private ?OutputLoggerInterface $outputLogger = null;
    private ?bool $isDryRun = null;
    private int $blocksDeleted = 0;
    private int $nodesDeleted = 0;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AssociationFinder $associationFinder
    )
    {
    }

    public function setBlockManager(BlockManager $blockManager): void
    {
        $this->blockManager = $blockManager;
    }

    public function clean(OutputLoggerInterface $outputLogger = null, bool $dryRun = false): void
    {
        $this->isDryRun = $dryRun;
        $this->outputLogger = $outputLogger !== null ? $outputLogger : new ChainOutputLogger();
        $this->blocksDeleted = 0;
        $this->nodesDeleted = 0;

        $this->outputLogger->info('Starting cleanup');
        $this->outputLogger->debug('');

        $this->cleanRootAndListNodesWithoutRootResource();
        $this->entityManager->clear();

        $this->cleanNodesWithoutParent();
        $this->entityManager->clear();

        $this->cleanBlockNodesWithoutBlock();
        $this->entityManager->clear();

        $this->cleanBlocksWithoutNode();

        $this->outputLogger->info('Cleanup complete, ' . $this->nodesDeleted . ' nodes and ' . $this->blocksDeleted . ' blocks deleted.');
    }

    /**
     * @throws \Exception
     */
    private function cleanRootAndListNodesWithoutRootResource()
    {
        $this->outputLogger->debug('Finding root/list nodes without resource');
        $nodesToDelete = $this->findRootAndListNodesWithoutRootResource();
        if (count($nodesToDelete) > 0) $this->outputLogger->debug('');
        $this->outputLogger->debug('done, ' . count($nodesToDelete) . ' found.');

        if (count($nodesToDelete) > 0) {
            $this->outputLogger->debug('Deleting');
            $this->deleteNodeTrees($nodesToDelete);
            $this->outputLogger->debug('');
            $this->outputLogger->debug('done.');
        }
        $this->outputLogger->debug('');
    }

    /**
     * @throws \Exception
     */
    private function cleanNodesWithoutParent()
    {
        $this->outputLogger->debug('Finding non-root nodes without parent');
        $nodesToDelete = $this->findNodesWithoutParent();
        if (count($nodesToDelete) > 0) $this->outputLogger->debug('');
        $this->outputLogger->debug('done, ' . count($nodesToDelete) . ' found.');

        if (count($nodesToDelete) > 0) {
            $this->outputLogger->debug('Deleting');
            $this->deleteNodeTrees($nodesToDelete);
            $this->outputLogger->debug('');
            $this->outputLogger->debug('done.');
        }
        $this->outputLogger->debug('');
    }

    /**
     * @throws \Exception
     */
    private function cleanBlockNodesWithoutBlock()
    {
        $this->outputLogger->debug('Finding block nodes without blocks');
        $nodesToDelete = $this->findBlockNodesWithoutBlock();
        if (count($nodesToDelete) > 0) $this->outputLogger->debug('');
        $this->outputLogger->debug('done, ' . count($nodesToDelete) . ' found.');

        if (count($nodesToDelete) > 0) {
            $this->outputLogger->debug('Deleting');
            $this->deleteNodeTrees($nodesToDelete);
            $this->outputLogger->debug('');
            $this->outputLogger->debug('done.');
        }
        $this->outputLogger->debug('');
    }

    private function cleanBlocksWithoutNode()
    {
        $this->outputLogger->debug('Finding blocks without node');
        $blocksToDelete = $this->findBlocksWithoutNode();
        if (count($blocksToDelete) > 0) $this->outputLogger->debug('');
        $this->outputLogger->debug('done, ' . count($blocksToDelete) . ' found.');

        if (count($blocksToDelete) > 0) {
            $this->outputLogger->debug('Deleting');
            $this->deleteSingleBlocks($blocksToDelete);
            $this->outputLogger->debug('');
            $this->outputLogger->debug('done');
        }
        $this->outputLogger->debug('');
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function findRootAndListNodesWithoutRootResource()
    {
        $nodesToDelete = [];

        $rootNodes = $this->entityManager->getRepository(Node::class)->findBy([
            'type' => Node::TYPE_ROOT
        ]);

        /** @var Node $rootNode */
        foreach($rootNodes as $rootNode) {
            $associations = $this->associationFinder->findAssociationsTo($rootNode, NodeInterface::class, [NodeInterface::class, BlockInterface::class]);
            if (count($associations) === 0) {
                $nodesToDelete[$rootNode->getId()] = $rootNode;
                $this->outputLogger->write('.', Level::Debug);
            }
        }

        $listNodes = $this->entityManager->getRepository(Node::class)->findBy([
            'type' => Node::TYPE_LIST
        ]);

        /** @var Node $listNode */
        foreach($listNodes as $listNode) {
            if ($listNode->getRoot() && isset($nodesToDelete[$listNode->getRoot()->getId()])) {
                // List node is part of a node tree that is already queued for deleting
                continue;
            }
            $associations = $this->associationFinder->findAssociationsTo($listNode, NodeInterface::class, [NodeInterface::class]);
            if (count($associations) === 0) {
                $nodesToDelete[$listNode->getId()] = $listNode;
                $this->outputLogger->write('.', Level::Debug);
            }
        }

        return $nodesToDelete;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function findBlockNodesWithoutBlock()
    {
        $nodesToDelete = [];

        $blockNodes = $this->entityManager->getRepository(Node::class)->findBy([
            'type' => Node::TYPE_BLOCK
        ]);

        /** @var Node $blockNode */
        foreach($blockNodes as $blockNode) {
            $associations = $this->associationFinder->findAssociationsTo($blockNode, NodeInterface::class, [NodeInterface::class]);
            if (count($associations) === 0) {
                $nodesToDelete []= $blockNode;
                $this->outputLogger->write('.', Level::Debug);
            }
        }

        return $nodesToDelete;
    }


    /**
     * @return array
     * @throws \Exception
     */
    private function findNodesWithoutParent()
    {
        $nodesToDelete = [];

        $nodes = $this->entityManager->getRepository(Node::class)->findBy([
            'parent' => null
        ]);

        /** @var Node $node */
        foreach($nodes as $node) {
            if ($node->getType() !== NodeInterface::TYPE_ROOT) {
                $nodesToDelete []= $node;
                $this->outputLogger->write('.', Level::Debug);
            }
        }

        return $nodesToDelete;
    }

    /**
     * @return array
     */
    private function findBlocksWithoutNode()
    {
        $blocksToDelete = [];

        $blockConfigurations = $this->blockManager->getBlocks();

        foreach($blockConfigurations as $blockConfiguration) {
            $blocks = $this->entityManager->getRepository($blockConfiguration->getModel())->findAll();

            /** @var BlockInterface $block */
            foreach($blocks as $block) {
                $node = $block->getNode();

                if ($node === null) {
                    $blocksToDelete []= $block;
                    $this->outputLogger->write('.', Level::Debug);
                }
            }
        }

        return $blocksToDelete;
    }

    /**
     * @param array $blocksToDelete
     */
    private function deleteSingleBlocks($blocksToDelete)
    {
        foreach($blocksToDelete as $block) {
            if (!$this->isDryRun) {
                $this->entityManager->remove($block);
            }
            $this->blocksDeleted++;
            $this->outputLogger->write('.', Level::Debug);
        }
        if (!$this->isDryRun) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param array $nodesToDelete
     */
    private function deleteNodeTrees($nodesToDelete)
    {
        foreach($nodesToDelete as $node) {
            $this->deleteNodeTreeRecursive($node);
        }

        if (!$this->isDryRun) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param NodeInterface $node
     */
    private function deleteNodeTreeRecursive(NodeInterface $node)
    {
        foreach($node->getChildren() as $child) {
            $this->deleteNodeTreeRecursive($child);
        }

        if ($node->getBlock()) {
            $this->blocksDeleted++;
            if (!$this->isDryRun) {
                $this->entityManager->remove($node->getBlock());
            }
            $this->outputLogger->write('.', Level::Debug);
        }

        $this->nodesDeleted++;
        if (!$this->isDryRun) {
            $this->entityManager->remove($node);
        }

        $this->outputLogger->write('.', Level::Debug);
    }
}
