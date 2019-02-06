<?php

namespace Enhavo\Bundle\GeneratorBundle\Generator;

use Enhavo\Bundle\RoutingBundle\AutoGenerator\GeneratorInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class GridItemGenerator
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var ConsoleOutputInterface
     */
    private $output;

    /**
     * @var GeneratorInterface
     */
    private $generator;

    public function __construct(KernelInterface $kernel, Generator $generator)
    {
        $this->kernel = $kernel;
        $this->generator = $generator;
        $this->output = new ConsoleOutput();
    }

    public function generateGridItem($bundleName, $itemName)
    {
        $bundle = $this->kernel->getBundle($bundleName);

        $itemSubDirectory = '';
        $this->splitItemNameSubDirectory($itemName, $itemSubDirectory);

        $this->generateDoctrineOrmFile($bundle, $itemName, $itemSubDirectory);
        $this->generateEntityFile($bundle, $itemName, $itemSubDirectory);
        $this->generateFormTypeFile($bundle, $itemName, $itemSubDirectory);
        $this->generateFactoryFile($bundle, $itemName, $itemSubDirectory);
        $this->generateTemplateFile($bundle, $itemName, $itemSubDirectory);

        $this->output->writeln('');
        $this->output->writeln('<options=bold>Add this to your enhavo.yml config file under enhavo_grid -> items:</>');
        $this->output->writeln($this->generateEnhavoConfigCode($bundle, $itemName, $itemSubDirectory));
        $this->output->writeln('');
    }

    private function generateDoctrineOrmFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
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

        $bundleNameSnakeCase = $this->generator->camelCaseToSnakeCase($this->generator->getBundleNameWithoutPostfix($bundle));
        $itemNameSnakeCase = $this->generator->camelCaseToSnakeCase($itemName);
        $itemSubDirectorySnakeCase = str_replace('/', '', $this->generator->camelCaseToSnakeCase($itemSubDirectory));

        $this->generator->renderFile(
            '@EnhavoGenerator/Generator/GridItem/doctrine.orm.yml.twig',
            $filePath,
            [
                'bundle_namespace' => $bundle->getNamespace(),
                'item_sub_directory' => str_replace('/', '\\', $itemSubDirectory),
                'item_name' => $itemName,
                'table_name' => $bundleNameSnakeCase . '_' . ($itemSubDirectorySnakeCase ? $itemSubDirectorySnakeCase . '_' : '') . $itemNameSnakeCase
            ]);
    }

    private function generateEntityFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Entity/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . '.php';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Entity "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . '" already exists in bundle "' . $bundle->getName() . '".');
        }

        $this->generator->renderFile(
            '@EnhavoGenerator/Generator/GridItem/entity.php.twig',
            $filePath,
            [
                'namespace' => $this->getNameSpace($bundle, '\\Entity', $itemSubDirectory),
                'item_name' => $itemName
            ]
        );
    }

    private function generateFormTypeFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Form/Type/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Type.php';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('FormType "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Type" already exists in bundle "' . $bundle->getName() . '".');
        }

        $this->generator->renderFile(
            '@EnhavoGenerator/Generator/GridItem/form-type.php.twig',
            $filePath,
            [
                'namespace' => $this->getNameSpace($bundle, '\\Form\\Type', $itemSubDirectory),
                'item_name' => $itemName,
                'item_namespace' => $this->getNameSpace($bundle, '\\Entity', $itemSubDirectory) . '\\' . $itemName,
                'form_type_name' => $this->getFormTypeName($bundle, $itemName, $itemSubDirectory)
            ]
        );
    }

    private function generateFactoryFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Factory/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Factory.php';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Factory class "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Factory" already exists in bundle "' . $bundle->getName() . '".');
        }

        $this->generator->renderFile(
            '@EnhavoGenerator/Generator/GridItem/factory.php.twig',
            $filePath,
            [
                'namespace' => $this->getNameSpace($bundle, '\\Factory', $itemSubDirectory),
                'item_name' => $itemName
            ]
        );
    }

    private function generateTemplateFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Resources/views/Theme/Grid/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $this->camelCaseToSnakeCase($itemName, true) . '.html.twig';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Frontend template file "' . $filePath . '" already exists.');
        }

        $this->generator->renderFile(
            '@EnhavoGenerator/Generator/GridItem/template.html.twig',
            $filePath,
            [
                'item_name' => $itemName,
            ]
        );
    }

    private function generateEnhavoConfigCode(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $itemNameSpace = $this->getNameSpace($bundle, '\\Entity', $itemSubDirectory) . '\\' . $itemName;
        $formTypeNamespace = $this->getNameSpace($bundle, '\\Form\\Type', $itemSubDirectory) . '\\' .$itemName . 'Type';
        $template = $bundle->getName() . ':Theme/Grid' . ($itemSubDirectory ? '/' . $itemSubDirectory : '') . ':' . $this->camelCaseToSnakeCase($itemName, true) . '.html.twig';
        $factoryNamespace = $this->getNameSpace($bundle, '\\Factory', $itemSubDirectory) . '\\' . $itemName . 'Factory';

        return $this->generator->render('@EnhavoGenerator/Generator/GridItem/enhavo_config_entry.yml.twig', array(
            'item_name' => $itemName,
            'bundle_name' => $bundle->getName(),
            'item_name_snake_case' => $this->generator->camelCaseToSnakeCase($itemName),
            'item_namespace' => $itemNameSpace,
            'form_type_namespace' => $formTypeNamespace,
            'template' => $template,
            'factory_namespace' => $factoryNamespace
        ));
    }

    private function getFormTypeName(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $itemSubDirectorySnakeCase = str_replace('/', '', $this->generator->camelCaseToSnakeCase($itemSubDirectory));

        return
            $this->generator->camelCaseToSnakeCase($this->generator->getBundleNameWithoutPostfix($bundle))
            . '_' . ($itemSubDirectorySnakeCase ? $itemSubDirectorySnakeCase . '_' : '') . $this->generator->camelCaseToSnakeCase($itemName);
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
}
