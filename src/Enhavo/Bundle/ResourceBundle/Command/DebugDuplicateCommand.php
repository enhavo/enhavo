<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:14
 */

namespace Enhavo\Bundle\ResourceBundle\Command;

use Enhavo\Bundle\ResourceBundle\Duplicate\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class DebugDuplicateCommand extends Command
{
    public function __construct(
        private readonly MetadataRepository $metadataRepository
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('debug:duplicate')
            ->addArgument('name', InputArgument::REQUIRED, 'FQCN')
            ->setDescription('Show duplicate infos of class')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        /** @var $metadata Metadata */
        $metadata = $this->metadataRepository->getMetadata($name);

        if ($metadata === null) {
            $output->writeln(sprintf('No metadata found for "%s"', $name));
            return Command::SUCCESS;
        }

        $output->writeln(sprintf('<info>Metadata for "%s"</info>', $name));
        $output->writeln(Yaml::dump($metadata->getProperties(), 3));

        return Command::SUCCESS;
    }
}
