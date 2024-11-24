<?php
/**
 * MakeMenu.php
 *
 * @since 25/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeAction extends AbstractMaker
{
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates a new menu')
            ->addArgument('bundle', InputArgument::REQUIRED, 'What is the Bundle name?')
            ->addArgument('name', InputArgument::REQUIRED, 'What is the name of your action?')
        ;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:action';
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $bundleName = $input->getArgument('bundle');
        $name = $input->getArgument('name');
        $namespace = sprintf('%s\\Action', $this->util->getBundleNamespace($bundleName));
        $className = sprintf('%sActionType', $name);
        $class = sprintf("%s\\%s", $namespace, $className);

        $generator->generateClass(
            $class,
            $this->util->getRealpath('@EnhavoAppBundle/Resources/skeleton/Action.tpl.php'),
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
