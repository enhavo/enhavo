<?php

namespace Enhavo\Bundle\ApiBundle\Command;

use Enhavo\Bundle\ApiBundle\Documentation\DocumentationGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class CreateDocsCommand extends Command
{
    public function __construct(
        private DocumentationGenerator $documentationGenerator,
        private Filesystem $fs,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('enhavo:api:create-docs')
            ->setDescription('Create open api documentation')
            ->addArgument('output', InputArgument::REQUIRED, 'Output path')
            ->addArgument('section', InputArgument::OPTIONAL, 'Section', DocumentationGenerator::SECTION_DEFAULT)
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'perform a dry run, don\'t change anything')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $section = $input->getArgument('section');

        if (!$this->documentationGenerator->hasSection($section)) {
            $output->writeln("Section '{$section}' does not exist");
            return Command::FAILURE;
        }

        $outputPath = $input->getArgument('output');
        $data = $this->documentationGenerator->generate($section);
        $yaml = new Yaml();
        $this->fs->dumpFile($outputPath, $yaml->dump($data, PHP_INT_MAX));

        return Command::SUCCESS;
    }
}
