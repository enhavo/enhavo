<?php
/**
 * MakeRouting.php
 *
 * @since 28/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Maker;

use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class MakeRouting extends AbstractMaker
{
    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new routing file')
            ->addArgument('app', InputArgument::REQUIRED, 'What is the app name?')
            ->addArgument('resource', InputArgument::REQUIRED, 'What is the name of the resource?')
            ->addOption('What is the name of the resource?', null, InputOption::VALUE_REQUIRED, 'If the resource can be sorted, what is the property name to sort by?')
        ;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:routing';
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $generator->generateFile(
            '',
            'EnhavoAppBundle:Maker/routing.yml.twig',
            [
                'app' => $input->getArgument('app'),
                'resource' => $input->getArgument('resource'),
                'sorting' => $input->getArgument('sorting'),
                'app_url' => $this->getUrl($input->getArgument('app')),
                'resource_url' => $this->getUrl($input->getArgument('resource'))
            ]
        );
        $io->text('Next: Open your new controller class and add some pages!');
    }

    private function getUrl($input)
    {
        return preg_replace('/_/', '/', $input);
    }
}
