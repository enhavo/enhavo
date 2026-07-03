<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataManager;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'debug:structured-data',
    description: 'Debug structured data',
)]
class DebugStructuredDataCommand extends Command
{
    public function __construct(
        private StructuredDataManager $structuredDataManager,
        private ResourceManager $resourceManager,
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED, 'FQCN or resource name')
            ->addArgument('id', InputArgument::REQUIRED, 'Id of entity')
            ->addOption('groups', null, InputOption::VALUE_REQUIRED, 'Groups comma-separated');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityName = $input->getArgument('entity');
        $id = $input->getArgument('id');
        $groups = $input->getOption('groups') ? explode(',', $input->getOption('groups')) : null;

        $entity = null;
        if (class_exists($entityName)) {
            $repository = $this->em->getRepository($entity);
            $entity = $repository->find($id);
        } elseif ($this->resourceManager->getMetadata($entityName)) {
            $repository = $this->resourceManager->getRepository($entityName);
            $entity = $repository->find($id);
        } else {
            $output->writeln('<error>Entity "'.$entityName.'" is not a FCQN nor a valid resource name</error>');
            return Command::FAILURE;
        }

        if ($entity === null) {
            $output->writeln('<error>Entity with id "'.$id.'" not found</error>');
            return Command::FAILURE;
        }

        $data = $this->structuredDataManager->getData($entity, $groups);

        $output->writeln(print_r($data, true));

        return Command::SUCCESS;
    }
}
