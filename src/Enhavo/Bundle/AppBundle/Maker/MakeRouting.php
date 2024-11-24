<?php
/**
 * MakeRouting.php
 *
 * @since 28/06/15
 * @author gseidel
 */

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
use Symfony\Component\Console\Input\InputOption;

class MakeRouting extends AbstractMaker
{
    private NameTransformer $nameTransformer;

    public function __construct(
        private readonly MakerUtil $util
    )
    {
        $this->nameTransformer = new NameTransformer();
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription('Creates routing files')
            ->addArgument('resource', InputArgument::REQUIRED, 'What is the name of the resource?')
            ->addOption('bundle', null, InputOption::VALUE_NONE, 'Generate into bundle')
            ->addOption('grid', 'g', InputOption::VALUE_REQUIRED, 'Alternative grid name')
            ->addOption('input', 'i', InputOption::VALUE_REQUIRED, 'Alternative input name')
            ->addOption('area', null, InputOption::VALUE_REQUIRED, 'Area name (default: admin)', 'admin')
        ;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:routing';
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $resourceName = $input->getArgument('resource');
        $bundle = $input->getOption('bundle');
        $gridName = $input->getOption('grid') ?? $resourceName;
        $inputName = $input->getOption('input') ?? $resourceName;
        $areaName = $input->getOption('area');

        $split = explode('.', $resourceName);
        $namespace = strtolower($split[0]);
        $resource = strtolower($split[1]);

        if (!$bundle) {
            $routingPath = sprintf('%s/config/routes/%s/%s.yaml', $this->util->getProjectPath(), $areaName, $this->nameTransformer->snakeCase($resource));
            $routingApiPath = sprintf('%s/config/routes/%s_api/%s.yaml', $this->util->getProjectPath(), $areaName, $this->nameTransformer->snakeCase($resource));
        } else {
            $routingPath = sprintf('%s/Resources/config/routing/%s/%s.yaml', $this->util->getBundlePath($bundle), $areaName, $this->nameTransformer->snakeCase($resource));
            $routingApiPath = sprintf('%s/Resources/config/routing/%s_api/%s.yaml', $this->util->getBundlePath($bundle), $areaName, $this->nameTransformer->snakeCase($resource));
        }

        $routePrefix = $namespace . '_' . $areaName . '_' . $resource;
        $routeApiPrefix = $namespace . '_' . $areaName . '_api_' . $resource;
        $pathPrefix = $this->getUrl('/'. $namespace . '/' . $resource);
        $pathApiPrefix = $this->getUrl('/'. $namespace . '/' . $resource);

        $generator->generateFile(
            $routingPath,
            __DIR__ . '/../Resources/skeleton/routing.tpl.php',
            [
                'route_prefix' => $routePrefix,
                'route_api_prefix' => $routeApiPrefix,
                'path_prefix' => $pathPrefix,
                'resource' => $resource,
                'area' => $areaName,
            ]
        );

        $generator->generateFile(
            $routingApiPath,
            __DIR__ . '/../Resources/skeleton/routing_api.tpl.php',
            [
                'route_prefix' => $routePrefix,
                'route_api_prefix' => $routeApiPrefix,
                'path_prefix' => $pathPrefix,
                'resource' => $resource,
                'area' => $areaName,
                'grid_name' => $gridName,
                'input_name' => $inputName,
            ]
        );

        $generator->writeChanges();
    }

    private function getUrl($input)
    {
        return preg_replace('/_/', '-',  $this->nameTransformer->snakeCase($input));
    }
}
