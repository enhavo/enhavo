<?php

namespace Enhavo\Bundle\BlockBundle\CleanUp;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\Util\AssociationFinder;
use Symfony\Component\Console\Output\OutputInterface;

class CleanUpManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var BlockManager
     */
    private $blockManager;

    /**
     * @var AssociationFinder
     */
    private $associationFinder;

    /**
     * @var bool
     */
    private $isDryRun;

    /**
     * @var int
     */
    private $blocksDeleted;

    /**
     * @var int
     */
    private $nodesDeleted;

    /**
     * CleanUpCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param BlockManager $blockManager
     * @param AssociationFinder $associationFinder
     */
    public function __construct(EntityManagerInterface $entityManager, BlockManager $blockManager, AssociationFinder $associationFinder)
    {
        $this->entityManager = $entityManager;
        $this->blockManager = $blockManager;
        $this->associationFinder = $associationFinder;
    }

    /**
     * @param OutputInterface|null $output
     * @param bool $dryRun
     * @throws \Exception
     */
    public function cleanUp($output = null, $dryRun = false)
    {
        $this->isDryRun = $dryRun;
        $this->blocksDeleted = 0;
        $this->nodesDeleted = 0;

        $this->writelnIfVerbose('', $output);

        $this->writelnIfVerbose('Finding root/list nodes without resource', $output);
        $nodesToDelete = $this->findRootAndListNodesWithoutRootResource($output);
        if (count($nodesToDelete) > 0) $this->writelnIfVerbose('', $output);
        $this->writelnIfVerbose('done, ' . count($nodesToDelete) . ' found.', $output);

        if (count($nodesToDelete) > 0) {
            $this->writelnIfVerbose('Deleting', $output);
            $this->deleteNodeTrees($nodesToDelete, $output);
            $this->writelnIfVerbose('', $output);
            $this->writelnIfVerbose('done.', $output);
        }
        $this->writelnIfVerbose('', $output);

        $this->entityManager->clear();
        $this->writelnIfVerbose('Finding non-root nodes without parent', $output);
        $nodesToDelete = $this->findNodesWithoutParent($output);
        if (count($nodesToDelete) > 0) $this->writelnIfVerbose('', $output);
        $this->writelnIfVerbose('done, ' . count($nodesToDelete) . ' found.', $output);

        if (count($nodesToDelete) > 0) {
            $this->writelnIfVerbose('Deleting', $output);
            $this->deleteNodeTrees($nodesToDelete, $output);
            $this->writelnIfVerbose('', $output);
            $this->writelnIfVerbose('done.', $output);
        }
        $this->writelnIfVerbose('', $output);

        $this->entityManager->clear();
        $this->writelnIfVerbose('Finding block nodes without blocks', $output);
        $nodesToDelete = $this->findBlockNodesWithoutBlock($output);
        if (count($nodesToDelete) > 0) $this->writelnIfVerbose('', $output);
        $this->writelnIfVerbose('done, ' . count($nodesToDelete) . ' found.', $output);

        if (count($nodesToDelete) > 0) {
            $this->writelnIfVerbose('Deleting', $output);
            $this->deleteNodeTrees($nodesToDelete, $output);
            $this->writelnIfVerbose('', $output);
            $this->writelnIfVerbose('done.', $output);
        }
        $this->writelnIfVerbose('', $output);

        $this->entityManager->clear();
        $this->writelnIfVerbose('Finding blocks without node', $output);
        $blocksToDelete = $this->findBlocksWithoutNode($output);
        if (count($blocksToDelete) > 0) $this->writelnIfVerbose('', $output);
        $this->writelnIfVerbose('done, ' . count($blocksToDelete) . ' found.', $output);

        if (count($blocksToDelete) > 0) {
            $this->writelnIfVerbose('Deleting', $output);
            $this->deleteSingleBlocks($blocksToDelete, $output);
            $this->writelnIfVerbose('', $output);
            $this->writelnIfVerbose('done', $output);
        }
        $this->writelnIfVerbose('', $output);
    }

    /**
     * @return int
     */
    public function getBlocksDeleted()
    {
        return $this->blocksDeleted;
    }

    /**
     * @return int
     */
    public function getNodesDeleted()
    {
        return $this->nodesDeleted;
    }


    /**
     * @param OutputInterface|null $output
     * @return array
     * @throws \Exception
     */
    private function findRootAndListNodesWithoutRootResource($output)
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
                $this->writeIfVerbose('.', $output);
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
                $this->writeIfVerbose('.', $output);
            }
        }

        return $nodesToDelete;
    }

    /**
     * @param OutputInterface|null $output
     * @return array
     * @throws \Exception
     */
    private function findBlockNodesWithoutBlock($output)
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
                $this->writeIfVerbose('.', $output);
            }
        }

        return $nodesToDelete;
    }


    /**
     * @param OutputInterface|null $output
     * @return array
     * @throws \Exception
     */
    private function findNodesWithoutParent($output)
    {
        $nodesToDelete = [];

        $nodes = $this->entityManager->getRepository(Node::class)->findBy([
            'parent' => null
        ]);

        /** @var Node $node */
        foreach($nodes as $node) {
            if ($node->getType() !== NodeInterface::TYPE_ROOT) {
                $nodesToDelete []= $node;
                $this->writeIfVerbose('.', $output);
            }
        }

        return $nodesToDelete;
    }

    /**
     * @param OutputInterface|null $output
     * @return array
     */
    private function findBlocksWithoutNode($output)
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
                    $this->writeIfVerbose('.', $output);
                }
            }
        }

        return $blocksToDelete;
    }

    /**
     * @param array $blocksToDelete
     * @param OutputInterface|null $output
     */
    private function deleteSingleBlocks($blocksToDelete, $output)
    {
        foreach($blocksToDelete as $block) {
            if (!$this->isDryRun) {
                $this->entityManager->remove($block);
            }
            $this->blocksDeleted++;
            $this->writeIfVerbose('.', $output);
        }
        if (!$this->isDryRun) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param array $nodesToDelete
     * @param OutputInterface|null $output
     */
    private function deleteNodeTrees($nodesToDelete, $output)
    {
        foreach($nodesToDelete as $node) {
            $this->deleteNodeTreeRecursive($node, $output);
        }

        if (!$this->isDryRun) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param NodeInterface $node
     * @param OutputInterface|null $output
     */
    private function deleteNodeTreeRecursive(NodeInterface $node, $output)
    {
        foreach($node->getChildren() as $child) {
            $this->deleteNodeTreeRecursive($child, $output);
        }

        if ($node->getBlock()) {
            $this->blocksDeleted++;
            if (!$this->isDryRun) {
                $this->entityManager->remove($node->getBlock());
            }
            $this->writeIfVerbose('.', $output);
        }

        $this->nodesDeleted++;
        if (!$this->isDryRun) {
            $this->entityManager->remove($node);
        }

        $this->writeIfVerbose('.', $output);
    }

    /**
     * @param $message
     * @param OutputInterface $output
     */
    private function writeIfVerbose($message, $output)
    {
        if ($output && $output->isVerbose()) $output->write($message);
    }

    /**
     * @param $message
     * @param OutputInterface $output
     */
    private function writelnIfVerbose($message, $output)
    {
        if ($output && $output->isVerbose()) $output->writeln($message);
    }
}
