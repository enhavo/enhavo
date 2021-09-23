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
use Enhavo\Bundle\BlockBundle\Maker\Generator\PhpClass;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

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
     * @param array $config
     */
    public function __construct(MakerUtil $util, KernelInterface $kernel, NameTransformer $nameTransformer, array $config)
    {
        foreach ($config as $key => $value) {
            $this->name = $key;
            $this->config = $value;
            break;
        }

        $this->util = $util;
        $this->kernel = $kernel;
        $this->nameTransformer = $nameTransformer;

        if(preg_match('/Bundle$/', $this->getNamespace())) {
            $this->bundle = $this->kernel->getBundle($this->getNamespace());
            $this->setNamespace($this->bundle->getNamespace());
        }

        $nameParts = explode('/', $this->getName());
        $this->name = array_pop($nameParts);
        $this->subDirectories = $nameParts;
        $this->path = str_replace('\\', '/', $this->getNamespace());
        $this->nameTransformer = new NameTransformer();
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
            return sprintf( 'src/%s/Resources/views/theme/block/%s.html.twig', $this->path, $this->nameTransformer->kebabCase($this->name));
        } else {
            return sprintf('%s/templates/theme/block/%s.html.twig', $this->util->getProjectPath(), $this->nameTransformer->kebabCase($this->name));
        }
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
        $properties = $this->config['properties'];
        $namespace = sprintf('%s\Entity', $this->getNamespace());
        $class = new PhpClass($namespace, $this->getName(), AbstractBlock::class, $this->getUse(), $properties);
        $class->generateGetterSetters();

        return $class;
    }

    public function createFormTypePhpClass(): PhpClass
    {
        $formTypeName = sprintf('%s_%s', $this->nameTransformer->snakeCase($this->getApplicationName()), $this->nameTransformer->snakeCase($this->getName()));

        return new PhpClass($this->getFormNamespace(), $this->getName(), AbstractType::class, $this->getFormDefinition()->getUse(), []);
    }

    public function createDoctrineOrmYaml(): DoctrineOrmYaml
    {
        $properties = $this->getProperties();
        $namespace = sprintf('%s\Entity', $this->getNamespace());
        $applicationName = $this->nameTransformer->snakeCase($this->getApplicationName());
        $applicationName = str_replace('enhavo_', '', $applicationName); // special case for enhavo
        $tableName = sprintf('%s_%s', $applicationName, $this->nameTransformer->snakeCase($this->getName()));

        return new DoctrineOrmYaml($namespace, $this->getName(), $tableName, $properties);
    }

    public function getFormDefinition(): FormDefinition
    {
        return new FormDefinition($blockPrefix, $this->getProperties());
    }

    public function getProperties(): array
    {
        return $this->config['properties'];
    }

    public function getNamespace(): string
    {
        return $this->config['namespace'] ?? 'App';
    }

    public function setNamespace($namespace): void
    {
        $this->config['namespace'] = $namespace;
    }

    private function getUse()
    {
        return $this->config['use'] ?? [];
    }

}
