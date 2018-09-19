<?php

namespace Enhavo\Bundle\GeneratorBundle\Generator;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class GridItemGenerator extends Generator
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var ConsoleOutputInterface
     */
    protected $output;

    public function __construct(KernelInterface $kernel, EngineInterface $twigEngine)
    {
        parent::__construct($twigEngine);
        $this->kernel = $kernel;
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

    protected function generateDoctrineOrmFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
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

        $bundleNameSnakeCase = $this->camelCaseToSnakeCase($this->getBundleNameWithoutPostfix($bundle));
        $itemNameSnakeCase = $this->camelCaseToSnakeCase($itemName);
        $itemSubDirectorySnakeCase = str_replace('/', '', $this->camelCaseToSnakeCase($itemSubDirectory));

        if (!$this->renderFile(
            '@EnhavoGenerator/Generator/GridItem/doctrine.orm.yml.twig',
            $filePath,
            array(
                'bundle_namespace' => $bundle->getNamespace(),
                'item_sub_directory' => str_replace('/', '\\', $itemSubDirectory),
                'item_name' => $itemName,
                'table_name' => $bundleNameSnakeCase . '_' . ($itemSubDirectorySnakeCase ? $itemSubDirectorySnakeCase . '_' : '') . $itemNameSnakeCase
            )))
        {
            throw new \RuntimeException('Error writing file "' . $filePath . '".');
        }
    }

    protected function generateEntityFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Entity/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . '.php';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Entity "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . '" already exists in bundle "' . $bundle->getName() . '".');
        }

        if (!$this->renderFile(
            '@EnhavoGenerator/Generator/GridItem/entity.php.twig',
            $filePath,
            array(
                'namespace' => $this->getNameSpace($bundle, '\\Entity', $itemSubDirectory),
                'item_name' => $itemName
            )))
        {
            throw new \RuntimeException('Error writing file "' . $filePath . '".');
        }
    }

    protected function generateFormTypeFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Form/Type/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Type.php';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('FormType "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Type" already exists in bundle "' . $bundle->getName() . '".');
        }

        if (!$this->renderFile(
            '@EnhavoGenerator/Generator/GridItem/form-type.php.twig',
            $filePath,
            array(
                'namespace' => $this->getNameSpace($bundle, '\\Form\\Type', $itemSubDirectory),
                'item_name' => $itemName,
                'item_namespace' => $this->getNameSpace($bundle, '\\Entity', $itemSubDirectory) . '\\' . $itemName,
                'form_type_name' => $this->getFormTypeName($bundle, $itemName, $itemSubDirectory)
            )))
        {
            throw new \RuntimeException('Error writing file "' . $filePath . '".');
        }
    }

    protected function generateFactoryFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Factory/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Factory.php';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Factory class "' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $itemName . 'Factory" already exists in bundle "' . $bundle->getName() . '".');
        }

        if (!$this->renderFile(
            '@EnhavoGenerator/Generator/GridItem/factory.php.twig',
            $filePath,
            array(
                'namespace' => $this->getNameSpace($bundle, '\\Factory', $itemSubDirectory),
                'item_name' => $itemName
            )))
        {
            throw new \RuntimeException('Error writing file "' . $filePath . '".');
        }
    }

    protected function generateTemplateFile(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $filePath = $bundle->getPath() . '/Resources/views/Theme/Grid/' . ($itemSubDirectory ? $itemSubDirectory . '/' : '') . $this->camelCaseToSnakeCase($itemName, true) . '.html.twig';
        $this->createPathToFileIfNotExists($filePath);
        if (file_exists($filePath)) {
            throw new \RuntimeException('Frontend template file "' . $filePath . '" already exists.');
        }

        if (!$this->renderFile(
            '@EnhavoGenerator/Generator/GridItem/template.html.twig',
            $filePath,
            array(
                'item_name' => $itemName,
            )))
        {
            throw new \RuntimeException('Error writing file "' . $filePath . '".');
        }
    }

    protected function generateEnhavoConfigCode(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $itemNameSpace = $this->getNameSpace($bundle, '\\Entity', $itemSubDirectory) . '\\' . $itemName;
        $formTypeNamespace = $this->getNameSpace($bundle, '\\Form\\Type', $itemSubDirectory) . '\\' .$itemName . 'Type';
        $template = $bundle->getName() . ':Theme/Grid' . ($itemSubDirectory ? '/' . $itemSubDirectory : '') . ':' . $this->camelCaseToSnakeCase($itemName, true) . '.html.twig';
        $factoryNamespace = $this->getNameSpace($bundle, '\\Factory', $itemSubDirectory) . '\\' . $itemName . 'Factory';

        return $this->twigEngine->render('@EnhavoGenerator/Generator/GridItem/enhavo_config_entry.yml.twig', array(
            'item_name' => $itemName,
            'bundle_name' => $bundle->getName(),
            'item_name_snake_case' => $this->camelCaseToSnakeCase($itemName),
            'item_namespace' => $itemNameSpace,
            'form_type_namespace' => $formTypeNamespace,
            'template' => $template,
            'factory_namespace' => $factoryNamespace
        ));
    }

    protected function getFormTypeName(BundleInterface $bundle, $itemName, $itemSubDirectory)
    {
        $itemSubDirectorySnakeCase = str_replace('/', '', $this->camelCaseToSnakeCase($itemSubDirectory));

        return
            $this->camelCaseToSnakeCase($this->getBundleNameWithoutPostfix($bundle))
            . '_' . ($itemSubDirectorySnakeCase ? $itemSubDirectorySnakeCase . '_' : '') . $this->camelCaseToSnakeCase($itemName);
    }

    protected function splitItemNameSubDirectory(&$itemName, &$subDirectory) {
        $subDirectory = null;
        $matches = array();
        if (preg_match('/^(.*)\/([^\/]*)$/', $itemName, $matches)) {
            $subDirectory = $matches[1];
            $itemName = $matches[2];
        }
    }

    protected function getNameSpace(BundleInterface $bundle, $staticPath, $itemSubDirectory)
    {
        return $bundle->getNamespace() . $staticPath . ($itemSubDirectory ? '\\' . str_replace('/', '\\', $itemSubDirectory) : '');
    }

    protected function createPathToFileIfNotExists($fullFileName)
    {
        $info = pathinfo($fullFileName);
        if (!file_exists($info['dirname'])) {
            mkdir($info['dirname'], 0777, true);
        }
    }
}
