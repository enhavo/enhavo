<?php

namespace Enhavo\Bundle\AppBundle\Maker;

use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author gseidel
 */
class MakeMenu extends AbstractMaker
{
    /**
     * @var MakerUtil
     */
    private $util;

    /**
     * @var NameTransformer
     */
    private $nameTransformer;

    public function __construct(MakerUtil $util)
    {
        $this->util = $util;
        $this->nameTransformer = new NameTransformer();
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new menu')
            ->addArgument('bundle', InputArgument::REQUIRED, 'What is the Bundle name?')
            ->addArgument('name', InputArgument::REQUIRED, 'What is the name of your menu?')
        ;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:menu';
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $bundleName = $input->getArgument('bundle');
        $name = $input->getArgument('name');
        $namespace = sprintf('%s\\Menu', $this->util->getBundleNamespace($bundleName));
        $className = sprintf('%sMenuType', $name);
        $class = sprintf("%s\\%s", $namespace, $className);

        $generator->generateClass(
            $class,
            $this->util->getRealpath('@EnhavoAppBundle/Resources/skeleton/Menu.tpl.php'),
            [
                'namespace' => $namespace,
                'class_name' => $className,
                'name' => $this->nameTransformer->snakeCase($name)
            ]
        );
        $generator->writeChanges();
        $io->writeln('Register your class as a service');
    }
}
