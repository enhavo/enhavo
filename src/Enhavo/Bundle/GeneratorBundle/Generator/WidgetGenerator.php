<?php

namespace Enhavo\Bundle\GeneratorBundle\Generator;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class WidgetGenerator extends Generator
{
    const TEMPLATE_FILE_PATH = 'Theme/Widget';

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

    public function generateWidget($bundleName, $widgetName)
    {
        $bundle = $this->kernel->getBundle($bundleName);
        $cleanedWidgetName = $this->cleanWidgetName($widgetName);

        $this->generateWidgetClassFile($bundle, $cleanedWidgetName);
        $this->generateTemplateFile($bundle, $cleanedWidgetName);

        $this->output->writeln('');
        $this->output->writeln('<options=bold>Add this to your services.yml config file:</>');
        $this->output->writeln($this->generateServiceConfigCode($bundle, $cleanedWidgetName));
        $this->output->writeln('');
        $this->output->writeln('<options=bold>To render your widget, add this code in a twig file:</>');
        $this->output->writeln('{{ theme_widget_render(\'' . $this->camelCaseToSnakeCase($cleanedWidgetName) . '\', {}) }}');
        $this->output->writeln('');
    }

    protected function generateWidgetClassFile(BundleInterface $bundle, $widgetName)
    {
        $filePath = $bundle->getPath() . '/Widget/' . $widgetName . 'Widget.php';
        if (file_exists($filePath)) {
            throw new \RuntimeException('Class file "' . $filePath . '" already exists.');
        }

        if (!$this->renderFile(
            '@EnhavoGenerator/Generator/Widget/widget-class.php.twig',
            $filePath,
            array(
                'namespace' => $bundle->getNamespace() . '\\Widget',
                'template_file' => '@' . $this->getBundleNameWithoutPostfix($bundle) . '/' . self::TEMPLATE_FILE_PATH . '/' . $this->camelCaseToSnakeCase($widgetName) . '.html.twig',
                'widget_name' => $widgetName,
                'widget_label' => $this->camelCaseToSnakeCase($widgetName)
            )))
        {
            throw new \RuntimeException('Error writing file "' . $filePath . '".');
        }
    }

    protected function generateTemplateFile(BundleInterface $bundle, $widgetName)
    {
        $filePath = $bundle->getPath() . '/Resources/views/' . self::TEMPLATE_FILE_PATH . '/' . $this->camelCaseToSnakeCase($widgetName) . '.html.twig';
        if (file_exists($filePath)) {
            throw new \RuntimeException('Template file "' . $filePath . '" already exists.');
        }

        if (!$this->renderFile(
            '@EnhavoGenerator/Generator/Widget/template.html.twig',
            $filePath,
            array(
                'widget_name' => $widgetName
            )))
        {
            throw new \RuntimeException('Error writing file "' . $filePath . '".');
        }
    }

    protected function generateServiceConfigCode(BundleInterface $bundle, $widgetName)
    {
        return $this->twigEngine->render('@EnhavoGenerator/Generator/Widget/service_config_entry.yml.twig', array(
            'bundle_name_snake_case_without_postfix' => $this->camelCaseToSnakeCase($this->getBundleNameWithoutPostfix($bundle)),
            'widget_label' => $this->camelCaseToSnakeCase($widgetName),
            'bundle_namespace' => $bundle->getNamespace(),
            'widget_name' => $widgetName
        ));
    }

    protected function cleanWidgetName($widgetName)
    {
        $cleanedWidgetName = $this->snakeCaseToCamelCase($widgetName);
        $matches = [];
        if (preg_match('/^(.+)Widget$/', $cleanedWidgetName, $matches)) {
            $cleanedWidgetName = $matches[1];
        }
        return $cleanedWidgetName;
    }
}
