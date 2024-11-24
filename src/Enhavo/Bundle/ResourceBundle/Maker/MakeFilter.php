<?php
/**
 * MakeFilter.php
 *
 * @since 25/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeFilter extends AbstractMaker
{
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates a new filter')
            ->addArgument('bundle', InputArgument::REQUIRED, 'What is the Bundle name?')
            ->addArgument('name', InputArgument::REQUIRED, 'What is the name of your filter?')
        ;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:filter';
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $bundleName = $input->getArgument('bundle');
        $name = $input->getArgument('name');
        $namespace = sprintf('%s\\Filter', $this->util->getBundleNamespace($bundleName));
        $className = sprintf('%sFilterType', $name);
        $class = sprintf("%s\\%s", $namespace, $className);

        $generator->generateClass(
            $class,
            $this->util->getRealpath('@EnhavoAppBundle/Resources/skeleton/Filter.tpl.php'),
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
