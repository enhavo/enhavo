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
    /**
     * @var MakerUtil
     */
    private $util;

    public function __construct(MakerUtil $util)
    {
        $this->util = $util;
    }

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
        $resource = $input->getArgument('resource');
        $app = $input->getArgument('app');
        $bundleName = sprintf('%sBundle', $this->util->snakeCaseToCamelCase($app));

        $generator->generateFile(
            sprintf('%s/Resources/config/routing/admin/%s.yml', $this->util->getBundlePath($bundleName), $this->util->camelCaseToSnakeCase($resource)),
            $this->util->getRealpath('@EnhavoAppBundle/Resources/skeleton/routing.tpl.php'),
            [
                'app' => $this->util->camelCaseToSnakeCase($app),
                'resource' => $this->util->camelCaseToSnakeCase($resource),
                'app_url' => $this->getUrl($app),
                'resource_url' => $this->getUrl($resource)
            ]
        );

        $generator->writeChanges();
    }

    private function getUrl($input)
    {
        return preg_replace('/_/', '/',  $this->util->camelCaseToSnakeCase($input));
    }
}
