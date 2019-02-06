<?php

namespace Enhavo\Bundle\GeneratorBundle\Generator;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class WidgetGenerator
{
    const TEMPLATE_FILE_PATH = 'Theme/Widget';

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var ConsoleOutputInterface
     */
    private $output;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * WidgetGenerator constructor.
     *
     * @param KernelInterface $kernel
     * @param Generator $generator
     */
    public function __construct(KernelInterface $kernel, Generator $generator)
    {
        $this->kernel = $kernel;
        $this->output = new ConsoleOutput();
        $this->generator = $generator;
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
        $this->output->writeln('{{ widget_render(\'' . $this->generator->camelCaseToSnakeCase($cleanedWidgetName) . '\', {}) }}');
        $this->output->writeln('');
    }

    private function generateWidgetClassFile(BundleInterface $bundle, $widgetName)
    {
        $filePath = $bundle->getPath() . '/Widget/' . $widgetName . 'Widget.php';
        if (file_exists($filePath)) {
            throw new \RuntimeException('Class file "' . $filePath . '" already exists.');
        }

        $this->generator->renderFile(
            '@EnhavoGenerator/Generator/Widget/widget-class.php.twig',
            $filePath,
            [
                'namespace' => $bundle->getNamespace() . '\\Widget',
                'template_file' => '@' . $this->generator->getBundleNameWithoutPostfix($bundle) . '/' . self::TEMPLATE_FILE_PATH . '/' . $this->generator->camelCaseToSnakeCase($widgetName) . '.html.twig',
                'widget_name' => $widgetName,
                'widget_label' => $this->generator->camelCaseToSnakeCase($widgetName)
            ]
        );
    }

    private function generateTemplateFile(BundleInterface $bundle, $widgetName)
    {
        $filePath = $bundle->getPath() . '/Resources/views/' . self::TEMPLATE_FILE_PATH . '/' . $this->generator->camelCaseToSnakeCase($widgetName) . '.html.twig';
        if (file_exists($filePath)) {
            throw new \RuntimeException('Template file "' . $filePath . '" already exists.');
        }

        $this->generator->renderFile(
            '@EnhavoGenerator/Generator/Widget/template.html.twig',
            $filePath,
            [
                'widget_name' => $widgetName
            ]
        );
    }

    private function generateServiceConfigCode(BundleInterface $bundle, $widgetName)
    {
        return $this->generator->render('@EnhavoGenerator/Generator/Widget/service_config_entry.yml.twig', array(
            'bundle_name_snake_case_without_postfix' => $this->generator->camelCaseToSnakeCase($this->generator->getBundleNameWithoutPostfix($bundle)),
            'widget_label' => $this->generator->camelCaseToSnakeCase($widgetName),
            'bundle_namespace' => $bundle->getNamespace(),
            'widget_name' => $widgetName
        ));
    }

    private function cleanWidgetName($widgetName)
    {
        $cleanedWidgetName = $this->generator->snakeCaseToCamelCase($widgetName);
        $matches = [];
        if (preg_match('/^(.+)Widget$/', $cleanedWidgetName, $matches)) {
            $cleanedWidgetName = $matches[1];
        }
        return $cleanedWidgetName;
    }
}
