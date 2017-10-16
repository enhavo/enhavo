<?php

namespace Enhavo\Bundle\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateGridItemCommand extends ContainerAwareCommand
{
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

        $generator = $this->getContainer()->get('enhavo_generator.generator.grid_item_generator');
        $generator->generateGridItem($bundleName, $itemName);
    }
}
