<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Command;

use Enhavo\Bundle\ResourceBundle\Duplicate\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'debug:duplicate',
    description: 'Show duplicate infos of class',
)]
class DebugDuplicateCommand extends Command
{
    public function __construct(
        private readonly MetadataRepository $metadataRepository,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'FQCN')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        /** @var $metadata Metadata */
        $metadata = $this->metadataRepository->getMetadata($name);

        if (null === $metadata) {
            $output->writeln(sprintf('No metadata found for "%s"', $name));

            return Command::SUCCESS;
        }

        $output->writeln(sprintf('<info>Metadata for "%s"</info>', $name));
        $output->writeln(Yaml::dump($metadata->getProperties(), 3));

        return Command::SUCCESS;
    }
}
