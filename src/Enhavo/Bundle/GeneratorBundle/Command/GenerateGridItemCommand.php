<?php

namespace Enhavo\Bundle\GeneratorBundle\Command;

use Enhavo\Bundle\GeneratorBundle\Generator\GridItemGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateGridItemCommand extends Command
{
    /**
     * @var GridItemGenerator
     */
    private $generator;

    /**
     * GenerateGridItemCommand constructor.
     *
     * @param GridItemGenerator $generator
     */
    public function __construct(GridItemGenerator $generator)
    {
        $this->generator = $generator;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:generate:grid-item')
            ->setDescription('Generate templates for new grid item')
            ->addArgument(
                'bundleName',
                InputArgument::REQUIRED,
                'What is the name of the bundle the new item should be added to?'
            )
            ->addArgument(
                'itemName',
                InputArgument::REQUIRED,
                'What is the name the item should have?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundleName = $input->getArgument('bundleName');
        $itemName = $input->getArgument('itemName');
        $this->generator->generateGridItem($bundleName, $itemName);
    }
}
