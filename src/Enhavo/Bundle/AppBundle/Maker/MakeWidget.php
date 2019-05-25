<?php

namespace Enhavo\Bundle\AppBundle\Maker;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class MakeWidget extends AbstractMaker
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
    private $util;

    /**
     * WidgetGenerator constructor.
     *
     * @param MakerUtil $util
     */
    public function __construct(MakerUtil $util)
    {
        $this->util = $util;
    }


    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new widget')
            ->addArgument(
                'bundleName',
                InputArgument::REQUIRED,
                'What is the name of the bundle the new widget should be added to?'
            )
            ->addArgument(
                'widgetName',
                InputArgument::REQUIRED,
                'What is the name the widget should have?'
            );
        ;
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:widget';
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        // TODO: Implement configureDependencies() method.
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {

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
        $this->output->writeln('{{ widget_render(\'' . $this->util->camelCaseToSnakeCase($cleanedWidgetName) . '\', {}) }}');
        $this->output->writeln('');
    }

    private function generateWidgetClassFile(BundleInterface $bundle, $widgetName)
    {
        $filePath = $bundle->getPath() . '/Widget/' . $widgetName . 'Widget.php';
        if (file_exists($filePath)) {
            throw new \RuntimeException('Class file "' . $filePath . '" already exists.');
        }

        $this->util->renderFile(
            '@EnhavoGenerator/Generator/Widget/widget-class.php.twig',
            $filePath,
            [
                'namespace' => $bundle->getNamespace() . '\\Widget',
                'template_file' => '@' . $this->util->getBundleNameWithoutPostfix($bundle) . '/' . self::TEMPLATE_FILE_PATH . '/' . $this->util->camelCaseToSnakeCase($widgetName) . '.html.twig',
                'widget_name' => $widgetName,
                'widget_label' => $this->util->camelCaseToSnakeCase($widgetName)
            ]
        );
    }

    private function generateTemplateFile(BundleInterface $bundle, $widgetName)
    {
        $filePath = $bundle->getPath() . '/Resources/views/' . self::TEMPLATE_FILE_PATH . '/' . $this->util->camelCaseToSnakeCase($widgetName) . '.html.twig';
        if (file_exists($filePath)) {
            throw new \RuntimeException('Template file "' . $filePath . '" already exists.');
        }

        $this->util->renderFile(
            '@EnhavoGenerator/Generator/Widget/template.html.twig',
            $filePath,
            [
                'widget_name' => $widgetName
            ]
        );
    }

    private function generateServiceConfigCode(BundleInterface $bundle, $widgetName)
    {
        return $this->util->render('@EnhavoGenerator/Generator/Widget/service_config_entry.yml.twig', array(
            'bundle_name_snake_case_without_postfix' => $this->util->camelCaseToSnakeCase($this->util->getBundleNameWithoutPostfix($bundle)),
            'widget_label' => $this->util->camelCaseToSnakeCase($widgetName),
            'bundle_namespace' => $bundle->getNamespace(),
            'widget_name' => $widgetName
        ));
    }

    private function cleanWidgetName($widgetName)
    {
        $cleanedWidgetName = $this->util->snakeCaseToCamelCase($widgetName);
        $matches = [];
        if (preg_match('/^(.+)Widget$/', $cleanedWidgetName, $matches)) {
            $cleanedWidgetName = $matches[1];
        }
        return $cleanedWidgetName;
    }
}
