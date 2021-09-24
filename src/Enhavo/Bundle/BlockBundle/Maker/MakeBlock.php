<?php

namespace Enhavo\Bundle\BlockBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;

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
     * @var Environment
     */
    private $twigEnvironment;

    /**
     * @var NameTransformer
     */
    private $nameTransformer;

    public function __construct(KernelInterface $kernel, MakerUtil $util, Environment $twigEnvironment)
    {
        $this->kernel = $kernel;
        $this->util = $util;
        $this->twigEnvironment = $twigEnvironment;
        $this->nameTransformer = new NameTransformer();
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:block';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates a new block from YAML or CLI')
            ->addOption('file', 'f', InputOption::VALUE_OPTIONAL, 'Path to a block definition yaml file')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Path containing block definition yaml files')
//            ->addArgument(
//                'namespace',
//                InputArgument::REQUIRED,
//                'What is the name of the bundle or namespace?'
//            )
//            ->addArgument(
//                'name',
//                InputArgument::REQUIRED,
//                'What is the name the block should have (Including "Block" postfix; Directories allowed, e.g. "MyDir/MyBlock")?'
//            )
//            ->addArgument(
//                'type',
//                InputArgument::REQUIRED,
//                'Create block type? [no/yes]'
//            )
        ;
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    private function generateBlockFromYamlFile($file, ConsoleStyle $io, Generator $generator)
    {
        $config = Yaml::parseFile($file);
        $definition = new BlockDefinition($this->util, $this->kernel, $this->nameTransformer, $config);
        $this->generateItemFiles($generator, $definition);
        $this->generateBlockEntityFile($generator, $definition);
        $this->generateBlockDoctrineOrmFile($generator, $definition);
        $this->generateBlockFactoryFile($generator, $definition);
        $this->generateBlockFormTypeFile($generator, $definition);
        $this->generateTemplateFile($generator, $definition);
    }

    private function generateBlocksFromPath($path, ConsoleStyle $io, Generator $generator)
    {
        $finder = new Finder();
        $finder->files()->in($path);

        foreach ($finder as $file) {
            $this->generateBlockFromYamlFile($file, $io, $generator);
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $file = $input->getOption('file');
        $path = $input->getOption('path');

        if ($file || $path) {
            $input->setInteractive(false);
            if ($file) {
                $this->generateBlockFromYamlFile(sprintf('%s/%s', $this->kernel->getProjectDir(), $file), $io, $generator);
            } else {
                $this->generateBlocksFromPath(sprintf('%s/%s', $this->kernel->getProjectDir(), $path), $io, $generator);
            }

        } else {
            $namespace = $input->getArgument('namespace');
            $name = $input->getArgument('name');
            $type = in_array($input->getArgument('type'), ['yes', 'YES', 'y', 'Y']);

            $this->generateBlock($namespace, $name, $type, $io, $generator);

        }

        $generator->writeChanges();
    }

    private function generateBlock(string $namespace, string $name, bool $type, ConsoleStyle $io, Generator $generator)
    {
        $block = new BlockDefinition($this->util, $this->kernel, $this->nameTransformer, [$name => [
            'namespace' => $namespace,
        ]]);

        $this->generateBlockDoctrineOrmFile($generator, $block);
        $this->generateBlockEntityFile($generator, $block);
        $this->generateBlockFormTypeFile($generator, $block);
        $this->generateBlockFactoryFile($generator, $block);
        $this->generateTemplateFile($generator, $block);

        if ($type) {
            $this->generateTypeFile($generator, $block);
        }

        $io->writeln('');
        $io->writeln('<options=bold>Add this to your enhavo.yaml config file under enhavo_block -> blocks:</>');
        $io->writeln($this->generateEnhavoConfigCode($block, $type));
        $io->writeln('');

        if ($type) {
            $io->writeln('<options=bold>Add this to your service.yaml config</>');
            $io->writeln($this->generateServiceCode($block));
        }
        $io->writeln('');
    }

    private function generateBlockDoctrineOrmFile(Generator $generator, BlockDefinition $blockDefinition)
    {
        $generator->generateFile(
            $blockDefinition->getDoctrineORMFilePath(),
            $this->createTemplatePath('block/doctrine.tpl.php'), [
                'definition' => $blockDefinition,
                'class' => $blockDefinition->createEntityPhpClass(),
                'yaml' => $blockDefinition->createDoctrineOrmYaml(),
            ]
        );
    }

    private function generateBlockEntityFile(Generator $generator, BlockDefinition $blockDefinition)
    {
        $generator->generateFile(
            $blockDefinition->getEntityFilePath(),
            $this->createTemplatePath('block/entity.tpl.php'),
            [
                'definition' => $blockDefinition,
                'class' => $blockDefinition->createEntityPhpClass(),
            ]
        );
    }

    private function generateItemEntityFile(Generator $generator, BlockDefinition $blockDefinition)
{
        $generator->generateFile(
            $blockDefinition->getEntityFilePath(),
            $this->createTemplatePath('block/item-entity.tpl.php'),
            [
                'definition' => $blockDefinition,
                'class' => $blockDefinition->createEntityPhpClass(),
            ]
        );
    }

    private function generateItemFiles(Generator $generator, BlockDefinition $blockDefinition)
    {
        $itemDefinition = $blockDefinition->createItemDefinition();
        if ($itemDefinition) {
            $this->generateItemEntityFile($generator, $itemDefinition);
            $this->generateBlockFormTypeFile($generator, $itemDefinition);
            $this->generateBlockDoctrineOrmFile($generator, $itemDefinition);
        }
    }

    private function generateBlockFormTypeFile(Generator $generator, BlockDefinition $blockDefinition)
    {
        $generator->generateFile(
            $blockDefinition->getFormTypeFilePath(),
            $this->createTemplatePath('block/form-type.tpl.php'),
            [
                'definition' => $blockDefinition->getFormDefinition(),
                'class' => $blockDefinition->createFormTypePhpClass(),
                'entity' => $blockDefinition->createEntityPhpClass(),
            ]
        );
    }

    private function generateBlockFactoryFile(Generator $generator, BlockDefinition $blockDefinition)
    {
        $generator->generateFile(
            $blockDefinition->getFactoryFilePath(),
            $this->createTemplatePath('block/factory.tpl.php'), [
                'definition' => $blockDefinition,
            ]
        );
    }

    private function generateTemplateFile(Generator $generator, BlockDefinition $block)
    {
        $generator->generateFile(
            $block->getTemplateFilePath(),
            $this->createTemplatePath('block/template.tpl.php'),
            [
                'name' => $this->nameTransformer->snakeCase($block->getName()),
            ]
        );
    }

    private function generateTypeFile(Generator $generator, BlockDefinition $block)
    {
        $generator->generateFile(
            $block->getTypeFilePath(),
            $this->createTemplatePath('block/block-type.tpl.php'),
            [
                'namespace' => $block->getNamespace(),
                'entity_namespace' => $block->getEntityNamespace(),
                'form_namespace' => $block->getFormNamespace(),
                'factory_namespace' => $block->getFactoryNamespace(),
                'name_snake' => $this->nameTransformer->snakeCase($block->getName()),
                'name_camel' => $this->nameTransformer->camelCase($block->getName()),
                'name_kebab' => $this->nameTransformer->kebabCase($block->getName()),
                'translation_domain' => $block->getTranslationDomain(),
            ]
        );
    }

    private function generateEnhavoConfigCode(BlockDefinition $block, $type)
    {
        return $this->twigEnvironment->render('@EnhavoBlock/maker/Block/enhavo_config_entry.yml.twig', array(
            'namespace' => $block->getNamespace(),
            'entity_namespace' => $block->getEntityNamespace(),
            'form_namespace' => $block->getFormNamespace(),
            'factory_namespace' => $block->getFactoryNamespace(),
            'name_snake' => $this->nameTransformer->snakeCase($block->getName()),
            'name_camel' => $this->nameTransformer->camelCase($block->getName()),
            'template_file' => sprintf('%s/%s.html.twig', 'theme/block/' ,str_replace('-block', '', $this->nameTransformer->kebabCase($block->getName()))),
            'translation_domain' => $block->getTranslationDomain(),
            'block_type' => $type,
        ));
    }

    private function generateServiceCode(BlockDefinition $block)
    {
        return $this->twigEnvironment->render('@EnhavoBlock/maker/Block/block_service.yml.twig', array(
            'namespace' => $block->getNamespace(),
            'type' => $this->nameTransformer->snakeCase($block->getName()),
            'name' => $block->getName(),
        ));
    }

    private function createTemplatePath($name)
    {
        return __DIR__.'/../Resources/skeleton/'.$name;
    }
}
