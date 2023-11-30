<?php

namespace Enhavo\Bundle\BlockBundle\Maker;

use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Factory\BlockFactory;
use Enhavo\Bundle\BlockBundle\Factory\NodeFactory;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Exception;
use ReflectionClass;
use ReflectionException;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Yaml\Yaml;

class MakeBlockEndpointData extends AbstractMaker
{
    private bool $overwriteExisting = false;

    public function __construct(
        private readonly BlockManager        $blockManager,
        private readonly BlockFactory        $blockFactory,
        private readonly KernelInterface     $kernel,
        private readonly Filesystem          $fileSystem,
        private readonly NormalizerInterface $normalizer,
        private readonly NodeFactory         $nodeFactory,
    )
    {

    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:block-endpoint-data';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription('Creates endpoint data from existing block definitions')
            ->addOption('blockName', 'b', InputOption::VALUE_OPTIONAL, 'Name of the block (e.g. text_picture)')
            ->addOption('overwrite', 'o', InputOption::VALUE_OPTIONAL, 'Set true to overwrite existing file(s)')
            ->addOption('groups', 'g', InputOption::VALUE_OPTIONAL, 'Define block assigned groups to filter (comma separated without spaces)', 'layout,content')
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $name = $input->getOption('blockName');
        $this->overwriteExisting = (bool)$input->getOption('overwrite');
        $groups = explode(',', $input->getOption('groups'));

        if ($name) {
            $blockTypes = [$name => $this->blockManager->getBlock($name)];
        } else {
            $blockTypes = $this->blockManager->getBlocks();
        }
        foreach ($blockTypes as $blockName => $blockType) {
            if (!count(array_intersect($groups, $blockType->getGroups()))) {
                continue;
            }

            $io->writeln(sprintf('Generating dummy data for block %s', $blockName));
            try {
                $yamlString = $this->generateYaml($blockName, $blockType);

                $outputPath = $this->getOutputPath($blockName);
                $this->checkExists($outputPath);
                $generator->generateFile($outputPath, $this->getTemplatePath('block.tpl.php'), ['yaml' => $yamlString]);
                $io->writeln("\033[32m%s (%s#%d) \033[0m");

            } catch (Exception $exception) {
                $io->writeln(sprintf('Failed due to:', $blockName));
                $io->writeln(sprintf("\033[31m%s (%s#%d) \033[0m\n", $exception->getMessage(), $exception->getFile(), $exception->getLine()));
            }
        }

        $generator->writeChanges();
    }

    /**
     * @throws ExceptionInterface
     * @throws ReflectionException
     */
    private function generateYaml(string $blockName, Block $blockType): string
    {
        $blockInstance = $this->blockFactory->createNew($blockName);

        $normalizedBlockNode = $this->hydrateBlockNode($blockName, $blockType, $blockInstance);

        return Yaml::dump($normalizedBlockNode, 99, 4, Yaml::DUMP_NULL_AS_TILDE);
    }

    /**
     * @throws ExceptionInterface
     * @throws ReflectionException
     */
    private function hydrateBlockNode(string $blockName, Block $blockType, BlockInterface $blockInstance): array
    {
        $blockNode = $this->nodeFactory->createNew();
        $blockNode->setBlock($blockInstance);
        $blockNode->setName($blockName);
        $blockNode->setTemplate($blockType->getTemplate());

        $normalized = $this->normalize($blockNode);

        $normalized['data']['block'] = $this->normalize($blockInstance);

        return $normalized;
    }

    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    private function normalize($object): array
    {
        $normalized = $this->normalizer->normalize($object, null, ['groups' => ['endpoint.block']]);
        $this->generateDummyValues($normalized, $object);

        return $normalized;
    }

    /**
     * @throws Exception
     */
    private function generateDummyValues(array &$normalized, mixed $objectOrClass): void
    {
        try {
            $reflection = new ReflectionClass($objectOrClass);
            foreach ($normalized as $key => $value) {
                $normalized[$key] = $value === null ? $this->generateDummyValue($reflection, $key) : $value;
            }
        } catch (ReflectionException $exception) {

        }
    }

    /**
     * @throws Exception
     */
    private function generateDummyValue(ReflectionClass $reflectionClass, string $propertyName)
    {
        $defaults = $reflectionClass->getDefaultProperties();
        $parent = $reflectionClass->getParentClass();
        $parentDefaults = $parent !== false ? $parent->getDefaultProperties() : [];

        $default = $defaults[$propertyName] ?? $parentDefaults[$propertyName] ?? null;

        if ($default !== null) {
            return $default;
        }

        $typeName = $this->getTypeName($reflectionClass, $propertyName);
        return $this->createDummyValueByTypeName($typeName);
    }

    /**
     * @throws Exception
     */
    private function createDummyValueByTypeName(?string $typeName)
    {
        switch ($typeName) {
            case 'integer':
            case 'int':
                return random_int(0, 100);
            case 'bool':
            case 'boolean':
                return boolval(random_int(0, 1));
            case 'string':
                return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        }
    }

    private function getTypeName(ReflectionClass $reflectionClass, string $propertyName): ?string
    {
        $property = $this->getProperty($reflectionClass, $propertyName);
        if ($property) {
            $type = $property->getType();

            if ($type) {
                return $type->getName();
            } else if ($comment = $property->getDocComment()) {
                if (str_contains($comment, '@var')) {
                    $found = preg_filter('/.+@var ([\w\d]+)/', '$1', preg_split('/\\n/', $comment));
                    if (count($found)) {
                        $type = array_pop($found);
                        return $type;
                    }
                }
            }
        }

        return null;
    }

    private function getProperty(ReflectionClass $reflectionClass, string $propertyName): ?\ReflectionProperty
    {
        try {
            return $reflectionClass->getProperty($propertyName);
        } catch (Exception $exception) {
            try {
                $parent = $reflectionClass->getParentClass();
                if ($parent) {
                    return $parent->getProperty($propertyName);
                }
            } catch (Exception $exception) {

            }
        }

        return null;
    }

    private function getOutputPath($blockName): string
    {
        return sprintf('%s/%s.yaml', $this->getBlockDataPath(), $blockName);
    }

    private function getDataPath(): string
    {
        $dataPath = sprintf('%s/%s', $this->kernel->getProjectDir(), 'data');
        if (!$this->fileSystem->exists($dataPath)) {
            $this->fileSystem->mkdir($dataPath);
        }
        return $dataPath;
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

    private function getBlockDataPath(): string
    {
        $blockDataPath = sprintf('%s/%s', $this->getDataPath(), 'block');
        if (!$this->fileSystem->exists($blockDataPath)) {
            $this->fileSystem->mkdir($blockDataPath);
        }
        return $blockDataPath;
    }

    private function getTemplatePath($name): string
    {
        return sprintf('%s/../Resources/skeleton/data/%s', __DIR__, $name);
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        // TODO: Implement configureDependencies() method.
    }
}
