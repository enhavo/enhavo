<?php

namespace Enhavo\Bundle\BlockBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Exception;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MakeBlock extends AbstractMaker
{
    private NameTransformer $nameTransformer;
    private bool $overwriteExisting = false;

    public function __construct(
        private KernelInterface $kernel,
        private MakerUtil $util,
        private Environment $twigEnvironment,
        private Filesystem $fileSystem,
    )
    {
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
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Path containing block definition yaml file(s)')
            ->addOption('overwrite', 'o', null, 'Set true to overwrite existing files');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {

    }

    /**
     * @throws Exception
     */
    private function generateFromYamlFile($file, ConsoleStyle $io, Generator $generator)
    {
        $config = Yaml::parseFile($file);
        $blockDefinition = new BlockDefinition($this->util, $this->kernel, $this->fileSystem, $config);

        $this->generateBlockItemFiles($generator, $blockDefinition);
        $this->generateBlockEntityFile($generator, $blockDefinition);
        $this->generateBlockFormTypeFile($generator, $blockDefinition);
        $this->generateTemplateFile($generator, $blockDefinition);

        if ($blockDefinition->getBlockType()) {
            $this->generateBlockTypeFile($generator, $blockDefinition);
        }

        $this->writeConfigMessage($blockDefinition, $io);
    }

    /**
     * @throws Exception
     */
    private function generateFromPath($path, ConsoleStyle $io, Generator $generator)
    {
        $finder = new Finder();
        $finder->files()->in($path)->depth(0);

        foreach ($finder as $file) {
            if ($file->isFile()) {
                $this->generateFromYamlFile($file, $io, $generator);
            }
        }
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if (!$input->getOption('path')) {
            $command
                ->addArgument(
                    'namespace',
                    InputArgument::REQUIRED
                )
                ->addArgument(
                    'name',
                    InputArgument::REQUIRED
                )
                ->addArgument(
                    'type',
                    InputArgument::REQUIRED
                );

            $helper = $command->getHelper('question');

            $input->setArgument('namespace', $helper->ask($input, $io, new Question("What is the name of the bundle or namespace? (Press return for 'App')\n", 'App')));
            do {
                $name = $helper->ask($input, $io, new Question("What is the name the block should have (Including 'Block' postfix; Directories allowed, e.g. 'MyDir/AcmeBlock')?\n"));
            } while (!$name);

            $input->setArgument('name', $name);
            $input->setArgument('type', $helper->ask($input, $io, new Question("Create block type? [no/yes] (Press return for 'no'\n", 'no')));
        }
    }

    /**
     * @throws Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $path = $input->getOption('path');
        $this->overwriteExisting = $input->getOption('overwrite');

        if ($path) {
            if (is_file($path)) {
                $this->generateFromYamlFile(sprintf('%s/%s', $this->kernel->getProjectDir(), $path), $io, $generator);

            } else {
                $this->generateFromPath(sprintf('%s/%s', $this->kernel->getProjectDir(), $path), $io, $generator);
            }

        } else {
            $this->generateFromInputArguments($input, $io, $generator);

        }

        $generator->writeChanges();
    }

    /**
     * @throws Exception
     */
    private function generateFromInputArguments(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $namespace = $input->getArgument('namespace');
        $name = $input->getArgument('name');
        $type = in_array($input->getArgument('type'), ['yes', 'YES', 'y', 'Y']);

        $block = new BlockDefinition($this->util, $this->kernel, $this->fileSystem, [
            $name => [
                'namespace' => $namespace,
                'block_type' => $type,
            ]
        ]);

        $this->generateBlockEntityFile($generator, $block);
        $this->generateBlockFormTypeFile($generator, $block);
        $this->generateTemplateFile($generator, $block);

        if ($block->getBlockType()) {
            $this->generateBlockTypeFile($generator, $block);
        }

        $this->writeConfigMessage($block, $io);
    }

    private function generateBlockEntityFile(Generator $generator, BlockDefinition $blockDefinition)
    {
        $filePath = $blockDefinition->getEntityFilePath();
        $this->checkExists($filePath);

        $generator->generateFile(
            $filePath,
            $this->getTemplatePath('block/entity.tpl.php'), [
                'definition' => $blockDefinition,
                'class' => $blockDefinition->createEntityPhpClass(),
                'orm' => $blockDefinition->createDoctrineOrmYaml(),
            ]
        );
    }

    private function generateItemEntityFile(Generator $generator, BlockDefinition $blockDefinition)
    {
        $filePath = $blockDefinition->getEntityFilePath();
        $this->checkExists($filePath);

        $generator->generateFile(
            $filePath,
            $this->getTemplatePath('block/item-entity.tpl.php'), [
                'definition' => $blockDefinition,
                'class' => $blockDefinition->createEntityPhpClass(),
                'orm' => $blockDefinition->createDoctrineOrmYaml(),
            ]
        );
    }

    /**
     * @throws Exception
     */
    private function generateBlockItemFiles(Generator $generator, BlockDefinition $blockDefinition)
    {
        $classes = $blockDefinition->getClasses();
        foreach ($classes as $class) {
            $this->generateItemEntityFile($generator, $class);
            $this->generateBlockFormTypeFile($generator, $class);
        }
    }

    private function generateBlockFormTypeFile(Generator $generator, BlockDefinition $blockDefinition)
    {
        $filePath = $blockDefinition->getFormTypeFilePath();
        $this->checkExists($filePath);

        $generator->generateFile(
            $filePath,
            $this->getTemplatePath('block/form-type.tpl.php'), [
                'form' => $blockDefinition->getFormType(),
                'class' => $blockDefinition->createFormTypePhpClass(),
                'entity' => $blockDefinition->createEntityPhpClass(),
            ]
        );
    }

    private function generateTemplateFile(Generator $generator, BlockDefinition $block)
    {
        $filePath = $block->getTemplateFilePath();
        if ($this->checkExists($filePath, true)) {
            $generator->generateFile(
                $filePath,
                $this->getTemplatePath('block/template.tpl.php'), [
                    'name' => $this->nameTransformer->snakeCase($block->getName()),
                ]
            );
        }
    }

    private function generateBlockTypeFile(Generator $generator, BlockDefinition $block)
    {
        $filePath = $block->getTypeFilePath();
        $this->checkExists($filePath);

        $generator->generateFile(
            $filePath,
            $this->getTemplatePath('block/block-type.tpl.php'), [
                'definition' => $block
            ]
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    private function generateEnhavoConfigCode(BlockDefinition $block): string
    {
        return $this->twigEnvironment->render('@EnhavoBlock/maker/block/enhavo_config_entry.yml.twig', [
            'definition' => $block,
            'template_file' => sprintf('theme/block/%s.html.twig', str_replace('-block', '', $block->getKebabName())),
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    private function generateServiceCode(BlockDefinition $block): string
    {
        return $this->twigEnvironment->render('@EnhavoBlock/maker/block/block_service.yml.twig', [
            'namespace' => $block->getNamespace(),
            'type' => $this->nameTransformer->snakeCase($block->getName()),
            'name' => $block->getName(),
        ]);
    }

    private function getTemplatePath($name): string
    {
        return sprintf('%s/../Resources/skeleton/%s', __DIR__, $name);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    private function writeConfigMessage(BlockDefinition $blockDefinition, ConsoleStyle $io)
    {
        $io->writeln('');
        $io->writeln('<options=bold>Add this to your enhavo_block.yaml config file under enhavo_block -> blocks:</>');
        $io->writeln($this->generateEnhavoConfigCode($blockDefinition));
        $io->writeln('');

        if ($blockDefinition->getBlockType()) {
            $io->writeln('<options=bold>Add this to your services.yaml config</>');
            $io->writeln($this->generateServiceCode($blockDefinition));
        }
        $io->writeln('');
    }

    private function checkExists($filePath, $noOverwrite = false): bool
    {
        if ($this->fileSystem->exists($filePath)) {
            if (!$noOverwrite && $this->overwriteExisting) {
                $this->fileSystem->remove($filePath);
                return true;
            }
            return false;
        }

        return true;
    }
}
