<?php

namespace Enhavo\Bundle\GridBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Templating\EngineInterface;

class MakeGridItem extends AbstractMaker
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var MakerUtil
     */
    private $util;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    public function __construct(KernelInterface $kernel, MakerUtil $util, EngineInterface $templateEngine)
    {
        $this->kernel = $kernel;
        $this->util = $util;
        $this->templateEngine = $templateEngine;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:grid-item';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
        ->setDescription('Creates a new grid item')
        ->addArgument(
            'bundleName',
            InputArgument::REQUIRED,
            'What is the name of the bundle the new item should be added to?'
        )
        ->addArgument(
            'itemName',
            InputArgument::REQUIRED,
            'What is the name the item should have?'
        );
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $bundleName = $input->getArgument('bundleName');
        $itemName = $input->getArgument('itemName');

        $bundle = $this->kernel->getBundle($bundleName);

        $itemSubDirectory = '';
        $this->splitItemNameSubDirectory($itemName, $itemSubDirectory);

        $this->generateDoctrineOrmFile($generator, $bundle, $itemName, $itemSubDirectory);
        $this->generateEntityFile($generator, $bundle, $itemName, $itemSubDirectory);
        $this->generateFormTypeFile($generator, $bundle, $itemName, $itemSubDirectory);
        $this->generateFactoryFile($generator, $bundle, $itemName, $itemSubDirectory);
        $this->generateTemplateFile($generator, $bundle, $itemName, $itemSubDirectory);

        $io->writeln('');
        $io->writeln('<options=bold>Add this to your enhavo.yml config file under enhavo_grid -> items:</>');
        $io->writeln($this->generateEnhavoConfigCode($bundle, $itemName, $itemSubDirectory));
        $io->writeln('');

        $generator->writeChanges();
    }

    private function generateDoctrineOrmFile(Generator $generator, BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $itemFileName = $itemName;
        if ($itemSubDirectory) {
            $itemFileName = str_replace('/', '.', $itemSubDirectory) . '.' . $itemName;
        }
        $filePath = $bundle->getPath() . '/Resources/config/doctrine/' . $itemFileName . '.orm.yml';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Entity "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . '" already exists in bundle "' . $bundle->getName() . '".');
        }

        $bundleNameSnakeCase = $this->util->camelCaseToSnakeCase($this->util->getBundleNameWithoutPostfix($bundle));
        $itemNameSnakeCase = $this->util->camelCaseToSnakeCase($itemName);
        $itemSubDirectorySnakeCase = str_replace('/', '', $this->util->camelCaseToSnakeCase($itemSubDirectory));

        $generator->generateFile(
            $filePath,
            $this->createTemplatePath('grid_item/doctrine.tpl.php'),
            [
                'bundle_namespace' => $bundle->getNamespace(),
                'item_sub_directory' => str_replace('/', '\\', $itemSubDirectory),
                'item_name' => $itemName,
                'table_name' => $bundleNameSnakeCase . '_' . ($itemSubDirectorySnakeCase ? $itemSubDirectorySnakeCase . '_' : '') . $itemNameSnakeCase
            ]
        );
    }

    private function generateEntityFile(Generator $generator, BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Entity/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . '.php';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Entity "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . '" already exists in bundle "' . $bundle->getName() . '".');
        }

        $generator->generateFile(
            $filePath,
            $this->createTemplatePath('grid_item/entity.tpl.php'),
            [
                'namespace' => $this->getNameSpace($bundle, '\\Entity', $itemSubDirectory),
                'item_name' => $itemName
            ]
        );
    }

    private function generateFormTypeFile(Generator $generator, BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Form/Type/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Type.php';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('FormType "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Type" already exists in bundle "' . $bundle->getName() . '".');
        }

        $generator->generateFile(
            $filePath,
            $this->createTemplatePath('grid_item/form_type.tpl.php'),
            [
                'namespace' => $this->getNameSpace($bundle, '\\Form\\Type', $itemSubDirectory),
                'item_name' => $itemName,
                'item_namespace' => $this->getNameSpace($bundle, '\\Entity', $itemSubDirectory) . '\\' . $itemName,
                'form_type_name' => $this->getFormTypeName($bundle, $itemName, $itemSubDirectory)
            ]
        );
    }

    private function generateFactoryFile(Generator $generator, BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Factory/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Factory.php';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Factory class "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Factory" already exists in bundle "' . $bundle->getName() . '".');
        }

        $generator->generateFile(
            $filePath,
            $this->createTemplatePath('grid_item/factory.tpl.php'),
            [
                'namespace' => $this->getNameSpace($bundle, '\\Factory', $itemSubDirectory),
                'item_name' => $itemName
            ]
        );
    }

    private function generateTemplateFile(Generator $generator, BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Resources/views/Theme/Grid/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $this->util->camelCaseToSnakeCase($itemName, true) . '.html.twig';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Frontend template file "' . $filePath . '" already exists.');
        }

        $generator->generateFile(
            $filePath,
            $this->createTemplatePath('grid_item/template.tpl.php'),
            [
                'item_name' => $itemName,
            ]
        );
    }

    private function generateEnhavoConfigCode(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $itemNameSpace = $this->getNameSpace($bundle, '\\Entity', $itemSubDirectory) . '\\' . $itemName;
        $formTypeNamespace = $this->getNameSpace($bundle, '\\Form\\Type', $itemSubDirectory) . '\\' .$itemName . 'Type';
        $template = $bundle->getName() . ':Theme/Grid' . ($itemSubDirectory ? '/' . $itemSubDirectory : '') . ':' . $this->util->camelCaseToSnakeCase($itemName, true) . '.html.twig';
        $factoryNamespace = $this->getNameSpace($bundle, '\\Factory', $itemSubDirectory) . '\\' . $itemName . 'Factory';

        return $this->templateEngine->render('@EnhavoGrid/Maker/GridItem/enhavo_config_entry.yml.twig', array(
            'item_name' => $itemName,
            'bundle_name' => $bundle->getName(),
            'item_name_snake_case' => $this->util->camelCaseToSnakeCase($itemName),
            'item_namespace' => $itemNameSpace,
            'form_type_namespace' => $formTypeNamespace,
            'template' => $template,
            'factory_namespace' => $factoryNamespace
        ));
    }

    private function getFormTypeName(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $itemSubDirectorySnakeCase = str_replace('/', '', $this->util->camelCaseToSnakeCase($itemSubDirectory));

        return
            $this->util->camelCaseToSnakeCase($this->util->getBundleNameWithoutPostfix($bundle))
            . '_' . ($itemSubDirectorySnakeCase ? $itemSubDirectorySnakeCase . '_' : '') . $this->util->camelCaseToSnakeCase($itemName);
    }

    private function splitItemNameSubDirectory(&$itemName, &$subDirectory)
    {
        $subDirectory = null;
        $matches = array();
        if (preg_match('/^(.*)\/([^\/]*)$/', $itemName, $matches)) {
            $subDirectory = $matches[1];
            $itemName = $matches[2];
        }
    }

    private function getNameSpace(BundleInterface $bundle, $staticPath, $itemSubDirectory)
    {
        return $bundle->getNamespace() . $staticPath . ($itemSubDirectory ? '\\' . str_replace('/', '\\', $itemSubDirectory) : '');
    }

    private function createPathToFileIfNotExists($fullFileName)
    {
        $info = pathinfo($fullFileName);
        if (!file_exists($info['dirname'])) {
            mkdir($info['dirname'], 0777, true);
        }
    }

    private function createTemplatePath($name)
    {
        return __DIR__.'/../Resources/skeleton/'.$name;
    }
}
