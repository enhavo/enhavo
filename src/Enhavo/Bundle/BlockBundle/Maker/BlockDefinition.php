<?php
/**
 * @author blutze-media
 * @since 2021-09-23
 */

namespace Enhavo\Bundle\BlockBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
use Enhavo\Bundle\BlockBundle\Maker\Generator\DoctrineOrmYaml;
use Enhavo\Bundle\BlockBundle\Maker\Generator\FormType;
use Enhavo\Bundle\BlockBundle\Maker\Generator\PhpClass;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class BlockDefinition
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var MakerUtil
     */
    private $util;

    /**
     * @var BundleInterface
     */
    private $namespace;

    /**
     * @var string
     */
    private $name;

    /**
     * @var NameTransformer
     */
    private $nameTransformer;

    /**
     * @var BundleInterface
     */
    private $bundle;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /** @var Filesystem */
    private $filesystem;

    /**
     * @var string
     */
    private $subDirectories;

    /**
     * @var string
     */
    private $path;

    /**
     * @param MakerUtil $util
     * @param KernelInterface $kernel
     * @param Filesystem $filesystem
     * @param array $config
     */
    public function __construct(MakerUtil $util, KernelInterface $kernel, Filesystem $filesystem, array $config)
    {
        foreach ($config as $key => $value) {
            $this->name = $key;
            $this->config = $value;
            break;
        }

        $this->util = $util;
        $this->kernel = $kernel;
        $this->filesystem = $filesystem;
        $this->nameTransformer = new NameTransformer();

        if (preg_match('/Bundle$/', $this->getNamespace())) {
            $this->bundle = $this->kernel->getBundle($this->getNamespace());
            $this->setNamespace($this->bundle->getNamespace());
        }

        $nameParts = explode('/', $this->getName());
        $this->name = array_pop($nameParts);
        $this->subDirectories = $nameParts;
        $this->path = str_replace('\\', '/', $this->getNamespace());

        $this->loadTemplates();

    }

    private function loadTemplates()
    {
        foreach ($this->getProperties() as $key => $property) {
            if (isset($property['template'])) {
                $config = Yaml::parseFile($this->getTemplatePath($property['template']));
                $this->config['properties'][$key] = array_merge($config, $this->config['properties'][$key]);
                unset($this->config['properties'][$key]['template']);
            }
        }
    }

    public function getDoctrineORMFilePath()
    {
        $subDirectory = $this->subDirectories ? implode('.', $this->subDirectories).'.' : '';
        $filename = sprintf('%s%s.orm.yml', $subDirectory, $this->nameTransformer->camelCase($this->name));

        if($this->bundle) {
            return sprintf( 'src/%s/Resources/config/doctrine/%s', $this->path, $filename);
        } else {
            return sprintf('%s/config/doctrine/%s', $this->util->getProjectPath(), $filename);
        }
    }

    public function getEntityFilePath()
    {
        $subDirectory = $this->subDirectories ? implode('/', $this->subDirectories).'/' : '';
        $filename = sprintf('%s%s.php', $subDirectory, $this->nameTransformer->camelCase($this->name));

        if($this->bundle) {
            return sprintf( 'src/%s/Entity/%s', $this->path, $filename);
        } else {
            return sprintf('%s/src/Entity/%s', $this->util->getProjectPath(), $filename);
        }
    }

    public function getEntityNamespace()
    {
        $subDirectory = $this->subDirectories ? '\\'.implode('\\', $this->subDirectories) : '';
        return sprintf( '%s\\Entity%s', $this->getNamespace(), $subDirectory);
    }

    public function getFormTypeFilePath()
    {
        $subDirectory = $this->subDirectories ? implode('/', $this->subDirectories).'/' : '';
        $filename = sprintf('%s%sType.php', $subDirectory, $this->nameTransformer->camelCase($this->name));

        if($this->bundle) {
            return sprintf( 'src/%s/Form/Type/%s', $this->path, $filename);
        } else {
            return sprintf('%s/src/Form/Type/%s', $this->util->getProjectPath(), $filename);
        }
    }

    public function getTemplateFilePath()
    {
        if($this->bundle) {
            return sprintf( 'src/%s/Resources/views/%s', $this->path, $this->getTemplateFileName());
        } else {
            return sprintf('%s/templates/%s', $this->util->getProjectPath(), $this->getTemplateFileName());
        }
    }

    public function getTemplateFileName()
    {
        return sprintf('theme/block/%s.html.twig', str_replace('-block', '', $this->getKebabName()));
    }

    public function getFactoryFilePath()
    {
        $subDirectory = $this->subDirectories ? implode('/', $this->subDirectories).'/' : '';
        $filename = sprintf('%s%sFactory.php', $subDirectory, $this->nameTransformer->camelCase($this->name));

        if($this->bundle) {
            return sprintf( 'src/%s/Factory/%s', $this->path, $filename);
        } else {
            return sprintf('%s/src/Factory/%s', $this->util->getProjectPath(), $filename);
        }
    }

    public function getTypeFilePath()
    {
        if($this->bundle) {
            return sprintf( 'src/%s/Block/%sType.php', $this->path, $this->name);
        } else {
            return sprintf('%s/src/Block/%sType.php', $this->util->getProjectPath(), $this->name);
        }
    }

    public function getFactoryNamespace()
    {
        $subDirectory = $this->subDirectories ? '\\'.implode('\\', $this->subDirectories) : '';
        return sprintf( '%s\\Factory%s', $this->getNamespace(), $subDirectory);
    }

    public function getFormNamespace()
    {
        $subDirectory = $this->subDirectories ? '\\'.implode('\\', $this->subDirectories) : '';
        return sprintf( '%s\\Form\\Type%s', $this->getNamespace(), $subDirectory);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSnakeName(): string
    {
        return $this->nameTransformer->snakeCase($this->getName());
    }

    public function getCamelName(): string
    {
        return $this->nameTransformer->camelCase($this->getName());
    }

    public function getKebabName(): string
    {
        return $this->nameTransformer->kebabCase($this->getName());
    }

    public function getFormTypeName(): string
    {
        return sprintf('%s%s', $this->nameTransformer->camelCase($this->name), 'Type');
    }

    public function getFactoryName(): string
    {
        return sprintf('%s%s', $this->nameTransformer->camelCase($this->name), 'Factory');
    }

    public function getTranslationDomain(): ?string
    {
        if($this->bundle) {
            return $this->bundle->getName();
        }
        return null;
    }

    public function getApplicationName(): string
    {
        if($this->bundle) {
            $bundleName = $this->util->getBundleNameWithoutPostfix($this->bundle->getName());
            return $this->nameTransformer->snakeCase($bundleName);
        } else {
            return $this->nameTransformer->snakeCase(explode('\\', $this->getNamespace()));
        }
    }

    public function createEntityPhpClass(): PhpClass
    {
        $class = new PhpClass($this->getEntityNamespace(), $this->getName(), AbstractBlock::class, $this->getUse(), $this->getProperties());
        $class->generateConstructor();
        $class->generateGettersSetters();
        $class->generateAddersRemovers();

        return $class;
    }

    public function getClasses(): array
    {
        $definitions = [];
        $classes = $this->getConfig('classes', []);
        foreach ($classes as $key => $config) {
            $definition = new BlockDefinition($this->util, $this->kernel, $this->filesystem, [
                $key => $config
            ]);
            $definition->addUse(sprintf('%s\%s', $this->getEntityNamespace(), $this->getName()));
            $this->addUse(sprintf('%s\%s', $definition->getEntityNamespace(), $definition->getName()));

            $definitions[] = $definition;
        }

        return $definitions;
    }

    public function getLabel(): string
    {
        return $this->getConfig('label') ?? $this->getCamelName();
    }

    public function createFormTypePhpClass(): PhpClass
    {
        return new PhpClass($this->getFormNamespace(), $this->getFormTypeName(), AbstractType::class, $this->getFormUse(), []);
    }

    public function createDoctrineOrmYaml(): DoctrineOrmYaml
    {
        $applicationName = $this->nameTransformer->snakeCase($this->getApplicationName());
        $applicationName = str_replace('enhavo_', '', $applicationName); // special case for enhavo
        $tableName = sprintf('%s_%s', $applicationName, $this->nameTransformer->snakeCase($this->getName()));

        return new DoctrineOrmYaml($tableName, $this->getProperties());
    }

    public function getFormType(): FormType
    {
        $blockPrefix = sprintf('%s_%s', $this->nameTransformer->snakeCase($this->getApplicationName()), $this->nameTransformer->snakeCase($this->getName()));

        return new FormType($blockPrefix, $this->getProperties());
    }

    public function getProperties(): array
    {
        return $this->config['properties'] ?? [];
    }

    public function getConfig(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    public function getProperty(string $key)
    {
        return isset($this->config['properties'][$key]) ?? $this->config['properties'][$key];
    }

    public function getNamespace()
    {
        return $this->namespace ?? $this->config['namespace'] ?? 'App';
    }

    public function setNamespace($namespace): void
    {
        $this->namespace = $namespace;
    }

    public function getGroupsString(): ?string
    {
        return isset($this->config['groups']) ? sprintf("[ '%s' ]\n", implode("', '", $this->config['groups'])) : null;
    }

    public function getBlockType(): ?bool
    {
        return $this->config['block_type'] ?? false;
    }

    /**
     * @param mixed $config
     */
    public function addUse($use)
    {
        if (!isset($this->config['use'])) {
            $this->config['use'] = [];
        }
        $this->config['use'][] = $use;
    }


    private function getUse()
    {
        return $this->config['use'] ?? [];
    }

    private function getFormUse()
    {
        return $this->config['form']['use'] ?? [];
    }

    private function getTemplatePath($template): ?string
    {
        $paths = [];
        $paths[] = sprintf('%s/%s.yaml', $this->util->getProjectPath(), $template);
        $paths[] = sprintf('%s/%s/%s.yaml', $this->util->getProjectPath(), 'config/block/templates', $template);
        $paths[] = sprintf('%s%s/%s.yaml', $this->util->getBundlePath('EnhavoBlockBundle'), 'Resources/block/templates', $template);
        foreach ($paths as $path) {
            if ($this->filesystem->exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
