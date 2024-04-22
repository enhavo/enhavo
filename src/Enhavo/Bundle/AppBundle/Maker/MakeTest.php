<?php
/**
 * MakeMenu.php
 *
 * @since 25/05/19
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\Analyze\TestClassAnalyzer;
use Enhavo\Bundle\AppBundle\Maker\Test\TestClassResolverInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeTest extends AbstractMaker
{
    public function __construct(
        private MakerUtil $util,
        private TestClassResolverInterface $testClassResolver,
    )
    {
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates a new test')
            ->addArgument('fqcn', InputArgument::REQUIRED, 'What is the FQCN?')
        ;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:test';
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $fqcn = $input->getArgument('fqcn');

        $classAnalyzer = new TestClassAnalyzer($fqcn);

        $testFqcl = sprintf('%s\\%s', $this->testClassResolver->getNamespace($fqcn), $this->testClassResolver->getClassName($fqcn));

        $generator->generateClass(
            $testFqcl,
            $this->util->getRealpath('@EnhavoAppBundle/Resources/skeleton/Test.tpl.php'),
            [
                'classAnalyzer' => $classAnalyzer,
                'classResolver' => $this->testClassResolver,
                'fqcn' => $fqcn,
            ]
        );
        $generator->writeChanges();
    }
}
