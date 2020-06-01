<?php

namespace Enhavo\Bundle\BlockBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Templating\EngineInterface;

class MakeBlock extends AbstractMaker
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
        return 'make:enhavo:block';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
        ->setDescription('Creates a new block')
        ->addArgument(
            'namespace',
            InputArgument::REQUIRED,
            'What is the name of the bundle or namespace?'
        )
        ->addArgument(
            'name',
            InputArgument::REQUIRED,
            'What is the name the block should have (Without "Block" postfix, but pre directories allowed)?'
        )
        ->addArgument(
            'type',
            InputArgument::REQUIRED,
            'Create block type? [no/yes]'
        );
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $namespace = $input->getArgument('namespace');
        $name = $input->getArgument('name');
        $type = 'yes' === $input->getArgument('type') ? true : false;

        $block = new BlockName($this->util, $this->kernel, $namespace, $name);

        $this->generateDoctrineOrmFile($generator, $block);
        $this->generateEntityFile($generator, $block);
        $this->generateFormTypeFile($generator, $block);
        $this->generateFactoryFile($generator, $block);
        $this->generateTemplateFile($generator, $block);

        if($type) {
            $this->generateTypeFile($generator, $block);
        }

        $io->writeln('');
        $io->writeln('<options=bold>Add this to your enhavo.yaml config file under enhavo_block -> blocks:</>');
        $io->writeln($this->generateEnhavoConfigCode($block, $type));
        $io->writeln('');

        if($type) {
            $io->writeln('<options=bold>Add this to your service.yaml config</>');
            $io->writeln($this->generateServiceCode($block));
        }
        $io->writeln('');

        $generator->writeChanges();
    }

    private function generateDoctrineOrmFile(Generator $generator, BlockName $block)
    {
        $applicationName = $this->util->snakeCase($block->getApplicationName());
        $applicationName = str_replace('enhavo_', '', $applicationName); // special case for enhavo
        $tableName = sprintf('%s_%s_block', $applicationName, $this->util->snakeCase($block->getName()));
        $generator->generateFile(
            $block->getDoctrineORMFilePath(),
            $this->createTemplatePath('block/doctrine.tpl.php'),
            [
                'namespace' => $block->getEntityNamespace(),
                'name' => $block->getName(),
                'table_name' => $tableName
            ]
        );
    }

    private function generateEntityFile(Generator $generator, BlockName $block)
    {
        $generator->generateFile(
            $block->getEntityFilePath(),
            $this->createTemplatePath('block/entity.tpl.php'),
            [
                'entity_namespace' => $block->getEntityNamespace(),
                'name' => $block->getName()
            ]
        );
    }

    private function generateFormTypeFile(Generator $generator, BlockName $block)
    {
        $formTypeName = sprintf('%s_%s_block', $this->util->snakeCase($block->getApplicationName()), $this->util->snakeCase($block->getName()));
        $generator->generateFile(
            $block->getFormTypeFilePath(),
            $this->createTemplatePath('block/form-type.tpl.php'),
            [
                'form_namespace' => $block->getFormNamespace(),
                'name' => $block->getName(),
                'entity_namespace' => $block->getEntityNamespace(),
                'form_type_name' => $formTypeName
            ]
        );
    }

    private function generateFactoryFile(Generator $generator, BlockName $block)
    {
        $generator->generateFile(
            $block->getFactoryFilePath(),
            $this->createTemplatePath('block/factory.tpl.php'),
            [
                'factory_namespace' => $block->getFactoryNamespace(),
                'name' => $block->getName()
            ]
        );
    }

    private function generateTemplateFile(Generator $generator, BlockName $block)
    {
        $generator->generateFile(
            $block->getTemplateFilePath(),
            $this->createTemplatePath('block/template.tpl.php'),
            [
                'name' => $this->util->snakeCase($block->getName()),
            ]
        );
    }

    private function generateTypeFile(Generator $generator, BlockName $block)
    {
        $generator->generateFile(
            $block->getTypeFilePath(),
            $this->createTemplatePath('block/block-type.tpl.php'),
            [
                'namespace' => $block->getNamespace(),
                'entity_namespace' => $block->getEntityNamespace(),
                'form_namespace' => $block->getFormNamespace(),
                'factory_namespace' => $block->getFactoryNamespace(),
                'name_snake' => $this->util->snakeCase($block->getName()),
                'name_camel' => $this->util->camelCase($block->getName()),
                'name_kebab' => $this->util->kebabCase($block->getName()),
                'translation_domain' => $block->getTranslationDomain(),
            ]
        );
    }

    private function generateEnhavoConfigCode(BlockName $block, $type)
    {
        return $this->templateEngine->render('@EnhavoBlock/maker/Block/enhavo_config_entry.yml.twig', array(
            'namespace' => $block->getNamespace(),
            'entity_namespace' => $block->getEntityNamespace(),
            'form_namespace' => $block->getFormNamespace(),
            'factory_namespace' => $block->getFactoryNamespace(),
            'name_snake' => $this->util->snakeCase($block->getName()),
            'name_camel' => $this->util->camelCase($block->getName()),
            'name_kebab' => $this->util->kebabCase($block->getName()),
            'translation_domain' => $block->getTranslationDomain(),
            'block_type' => $type,
        ));
    }

    private function generateServiceCode(BlockName $block)
    {
        return $this->templateEngine->render('@EnhavoBlock/maker/Block/block_service.yml.twig', array(
            'namespace' => $block->getNamespace(),
            'type' => $this->util->snakeCase($block->getName()),
            'name' => $block->getName(),
        ));
    }

    private function createTemplatePath($name)
    {
        return __DIR__.'/../Resources/skeleton/'.$name;
    }
}
