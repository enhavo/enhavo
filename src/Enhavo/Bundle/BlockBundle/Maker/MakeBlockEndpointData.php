<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Maker;

use Enhavo\Bundle\BlockBundle\Block\Block;
use Enhavo\Bundle\BlockBundle\Block\BlockManager;
use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
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
        private readonly BlockManager $blockManager,
        private readonly KernelInterface $kernel,
        private readonly Filesystem $fileSystem,
        private readonly NormalizerInterface $normalizer,
        private readonly FactoryInterface $nodeFactory,
    ) {
    }

    public static function getCommandName(): string
    {
        return 'make:enhavo:block-endpoint-data';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription('Creates endpoint data from existing block definitions')
            ->addOption('blockName', 'b', InputOption::VALUE_OPTIONAL, 'Name of the block (e.g. text_picture, dumps all with given groups if not specified)')
            ->addOption('groups', 'g', InputOption::VALUE_OPTIONAL, 'Define block assigned groups to filter (comma separated without spaces)', 'layout,content')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Choose either YAML or JSON as export format', 'yaml')
            ->addOption('overwrite', 'o', InputOption::VALUE_OPTIONAL, 'Set true to overwrite existing file(s)')
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $name = $input->getOption('blockName');
        $groups = explode(',', strtolower($input->getOption('groups')));
        $format = strtolower($input->getOption('format'));
        $this->overwriteExisting = (bool) $input->getOption('overwrite');

        if ($name) {
            $blockTypes = [$name => $this->blockManager->getBlock($name)];
        } else {
            $blockTypes = $this->blockManager->getBlocks();
        }

        foreach ($blockTypes as $blockName => $blockType) {
            if (!$name && !count(array_intersect($groups, $blockType->getGroups()))) {
                continue;
            }

            $io->write(sprintf('Generating dummy data for block %s...', $blockName));

            try {
                $normalizedData = $this->generateDataArray($blockName, $blockType);
                $dataAsString = $this->toFormatString($normalizedData, $format);
                $outputPath = $this->getOutputPath($blockName, $format);

                $this->checkExists($outputPath);
                $generator->generateFile($outputPath, $this->getTemplatePath('block.tpl.php'), ['content' => $dataAsString]);

                $io->writeln("\033[32mOK\033[0m");
            } catch (\Throwable $exception) {
                $io->writeln("\033[31mFAILED\033[0m");
                $io->writeln(sprintf("Reason: %s (%s#%d)\n", $exception->getMessage(), $exception->getFile(), $exception->getLine()));
            }
        }

        $generator->writeChanges();
    }

    /**
     * @throws ExceptionInterface
     * @throws \ReflectionException
     */
    private function generateDataArray(string $blockName, Block $blockType): array
    {
        $blockInstance = $this->blockManager->getFactory($blockName)->createNew();

        return $this->hydrateBlockNode($blockName, $blockType, $blockInstance);
    }

    private function toFormatString(array $dataArray, $format): string
    {
        if ('json' === $format) {
            return json_encode($dataArray, JSON_PRETTY_PRINT);
        }

        return Yaml::dump($dataArray, 99, 4, Yaml::DUMP_NULL_AS_TILDE);
    }

    /**
     * @throws ExceptionInterface
     * @throws \ReflectionException
     */
    private function hydrateBlockNode(string $blockName, Block $blockType, BlockInterface $blockInstance): array
    {
        /** @var Node $blockNode */
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
     * @throws \Exception
     */
    private function normalize($object): array
    {
        $normalized = $this->normalizer->normalize($object, null, ['groups' => ['endpoint.block']]);
        $this->generateDummyValues($normalized, $object);

        return $normalized;
    }

    /**
     * @throws \Exception
     */
    private function generateDummyValues(array &$normalized, mixed $objectOrClass): void
    {
        try {
            $reflection = new \ReflectionClass($objectOrClass);
            foreach ($normalized as $key => $value) {
                $normalized[$key] = null === $value ? $this->generateDummyValue($reflection, $key) : $value;
            }
        } catch (\ReflectionException $exception) {
        }
    }

    /**
     * @throws \Exception
     */
    private function generateDummyValue(\ReflectionClass $reflectionClass, string $propertyName)
    {
        $defaults = $reflectionClass->getDefaultProperties();
        $parent = $reflectionClass->getParentClass();
        $parentDefaults = false !== $parent ? $parent->getDefaultProperties() : [];

        $default = $defaults[$propertyName] ?? $parentDefaults[$propertyName] ?? null;

        if (null !== $default) {
            return $default;
        }

        $typeName = $this->getTypeName($reflectionClass, $propertyName);

        return $this->createDummyValueByTypeName($typeName);
    }

    /**
     * @throws \Exception
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

    private function getTypeName(\ReflectionClass $reflectionClass, string $propertyName): ?string
    {
        $property = $this->getProperty($reflectionClass, $propertyName);
        if ($property) {
            $type = $property->getType();

            if ($type) {
                return $type->getName();
            } elseif ($comment = $property->getDocComment()) {
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

    private function getProperty(\ReflectionClass $reflectionClass, string $propertyName): ?\ReflectionProperty
    {
        try {
            return $reflectionClass->getProperty($propertyName);
        } catch (\Exception $exception) {
            try {
                $parent = $reflectionClass->getParentClass();
                if ($parent) {
                    return $parent->getProperty($propertyName);
                }
            } catch (\Exception $exception) {
            }
        }

        return null;
    }

    private function getOutputPath(string $blockName, string $format): string
    {
        return sprintf('%s/%s.%s', $this->getBlockDataPath(), $blockName, $format);
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
