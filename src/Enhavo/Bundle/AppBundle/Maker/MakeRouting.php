<?php
/**
 * MakeRouting.php
 *
 * @since 28/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Maker;

use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeRouting extends AbstractMaker
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
            ->setDescription('Creates a new routing file')
            ->addArgument('resource', InputArgument::REQUIRED, 'What is the name of the resource?')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'What is the bundle name? Type "no" if no bundle is needed')
            ->addArgument('sortable', InputArgument::OPTIONAL, 'Is the resource sortable? [yes/no]')
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
        $bundle = $input->getArgument('bundle');

        if($bundle == 'no') {
            $app = 'app';
            $path = sprintf('%s/config/routes/admin/%s.yaml', $this->util->getProjectPath(), $this->nameTransformer->snakeCase($resource));
        } else {
            $app = $this->util->getBundleNameWithoutPostfix($bundle);
            $path = sprintf('%s/Resources/config/routing/admin/%s.yaml', $this->util->getBundlePath($bundle), $this->nameTransformer->snakeCase($resource));
        }

        $generator->generateFile(
            $path,
            $this->util->getRealpath('@EnhavoAppBundle/Resources/skeleton/routing.tpl.php'),
            [
                'app' => $this->nameTransformer->snakeCase($app),
                'resource' => $this->nameTransformer->snakeCase($resource),
                'app_url' => $this->getUrl($app),
                'resource_url' => $this->getUrl($resource)
            ]
        );

        $generator->writeChanges();
    }

    private function getUrl($input)
    {
        return preg_replace('/_/', '/',  $this->nameTransformer->snakeCase($input));
    }
}
