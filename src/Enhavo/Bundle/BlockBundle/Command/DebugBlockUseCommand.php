<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Output\CliOutputLogger;
use Enhavo\Bundle\AppBundle\Output\OutputLoggerInterface;
use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DebugBlockUseCommand extends Command
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlockManager */
    private $blockManager;

    /** @var OutputLoggerInterface */
    private $logger;

    /** @var Block[] */
    private $blockConfig;

    /**
     * FindBlockUsageCommand constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, BlockManager $blockManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->blockManager = $blockManager;
    }

    protected function configure()
    {
        $this
            ->setName('debug:block')
            ->setDescription('Find root resource of all blocks, all blocks of one type or one specific block')
            ->addArgument('type', InputArgument::OPTIONAL, '(Optional) block type to search for')
            ->addArgument('id', InputArgument::OPTIONAL, '(Optional) id of specific block');
    }

    /**
     * @throws \Exception
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $id = $input->getArgument('id');

        $this->logger = new CliOutputLogger(new SymfonyStyle($input, $output));
        $this->blockConfig = $this->blockManager->getBlocks();

        if ($type && !isset($this->blockConfig[$type])) {
            $this->logger->error(sprintf('Unknown block named "%s". Name must be a key registered under enhavo_block.blocks.', $type));
            $this->logger->info(sprintf('Registered blocks: %s', implode(', ', array_keys($this->blockConfig))));
            $this->logger->info('');

            return Command::FAILURE;
        }

        $this->find($type, $id);

        return Command::SUCCESS;
    }

    private function find($type, $id)
    {
        if ($type) {
            $this->findBlockType($type, $id);
        } else {
            foreach (array_keys($this->blockConfig) as $type) {
                $this->findBlockType($type, $id);
            }
        }
    }

    private function findBlockType($type, $id)
    {
        $className = $this->blockConfig[$type]->getModel();

        if ($id) {
            $this->logger->info(sprintf('Searching usage for blocks of type "%s" with id #%s:', $type, $id));

            $entity = $this->entityManager->getRepository($className)->find($id);
            if (!$entity) {
                $this->logger->error('Found no block of type '.$type.' with id '.$id);
                $this->logger->info('');

                return;
            }
            if (!($entity instanceof BlockInterface)) {
                $this->logger->error('Configuration error: Entity of type '.$className.' with id '.$id.' is not a Block');
                $this->logger->info('');

                return;
            }

            $this->findSingleEntity($entity);
            $this->logger->info('');
        } else {
            $this->logger->info(sprintf('Searching usage for blocks of type "%s":', $type));

            $entities = $this->entityManager->getRepository($className)->findAll();
            foreach ($entities as $entity) {
                $this->findSingleEntity($entity);
            }

            if (0 === count($entities)) {
                $this->logger->info('None found.');
            }
            $this->logger->info('');
        }
    }

    private function findSingleEntity($entity)
    {
        if ($entity instanceof BlockInterface) {
            $resource = $this->blockManager->findRootResource($entity);
            if (!$resource) {
                $this->logger->info('#'.$entity->getId().' -> Found no root resource for block, might be orphaned. Run enhavo:block:clean-up to delete erroneous block structures.');

                return;
            }
            $this->outputFoundConnection($entity, $resource);
        } else {
            $this->logger->error('Entity is not a Block');
        }
    }

    private function outputFoundConnection($entity, $resource)
    {
        $message = '#'.$entity->getId().' -> '.get_class($resource).', id = '.$resource->getId();
        if (method_exists($resource, 'getTenant')) {
            $message .= ', tenant = "'.$resource->getTenant().'"';
        }
        if (method_exists($resource, 'getTitle')) {
            $message .= ', title = "'.$resource->getTitle().'"';
        }
        if (method_exists($resource, 'getRoute')) {
            $route = $resource->getRoute();
            if ($route instanceof RouteInterface) {
                $message .= ', route = "'.$route->getStaticPrefix().'"';
            }
        }

        $this->logger->info($message);
    }
}
