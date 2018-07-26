<?php

namespace Enhavo\Bundle\GeneratorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateWidgetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('enhavo:generate:widget')
            ->setDescription('Generate new widget')
            ->addArgument(
                'bundleName',
                InputArgument::REQUIRED,
                'What is the name of the bundle the new widget should be added to?'
            )
            ->addArgument(
                'widgetName',
                InputArgument::REQUIRED,
                'What is the name the widget should have?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundleName = $input->getArgument('bundleName');
        $widgetName = $input->getArgument('widgetName');

        $generator = $this->getContainer()->get('enhavo_generator.generator.widget_generator');
        $generator->generateWidget($bundleName, $widgetName);
    }
}
