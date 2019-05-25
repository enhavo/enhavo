<?php
/**
 * MakeTaxonomy.php
 *
 * @since 25/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\TaxonomyBundle\Maker;

use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeTaxonomy extends AbstractMaker
{
    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new taxonomy')
            ->addArgument('bundle', InputArgument::REQUIRED, 'What is the Bundle name?')
            ->addArgument('name', InputArgument::REQUIRED, 'What is the name of your taxonomy?')
        ;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:taxonomy';
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $generator->generateFile(
            '',
            'EnhavoTaxonomyBundle:Maker/routing.yml.twig',
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
