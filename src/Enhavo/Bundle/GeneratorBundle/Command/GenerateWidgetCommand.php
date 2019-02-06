<?php

namespace Enhavo\Bundle\GeneratorBundle\Command;

use Enhavo\Bundle\GeneratorBundle\Generator\WidgetGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateWidgetCommand extends Command
{
    /**
     * @var WidgetGenerator
     */
    private $generator;

    /**
     * GenerateWidgetCommand constructor.
     * @param WidgetGenerator $generator
     */
    public function __construct(WidgetGenerator $generator)
    {
        $this->generator = $generator;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('make:enhavo:widget')
            ->setDescription('Creates a new widget')
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
        $this->generator->generateWidget($bundleName, $widgetName);
    }
}
