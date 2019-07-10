<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-10
 * Time: 22:28
 */

namespace Enhavo\Bundle\BlockBundle\Maker;

use Enhavo\Bundle\AppBundle\Maker\MakerUtil;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class BlockName
{
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
     * BlockName constructor.
     * @param MakerUtil $util
     * @param KernelInterface $kernel
     * @param string $namespace
     * @param string $name
     */
    public function __construct(MakerUtil $util, KernelInterface $kernel, string $namespace, string $name)
    {
        $this->util = $util;
        $this->kernel = $kernel;
        $this->name = $name;

        if(preg_match('/Bundle$/', $namespace)) {
            $this->bundle = $this->kernel->getBundle($namespace);
            $this->namespace = $this->bundle->getNamespace();
        } else {
            $this->namespace = $namespace;
        }

        $nameParts = explode('/', $name);
        $this->name = array_pop($nameParts);
        $this->subDirectories = $nameParts;
        $this->path = str_replace('\\', '/', $this->namespace);
    }

    public function getDoctrineORMFilePath()
    {
        $subDirectory = $this->subDirectories ? implode('.', $this->subDirectories).'.' : '';
        $filename = sprintf('%s%sBlock.orm.yaml', $subDirectory, $this->util->camelCase($this->name));

        if($this->bundle) {
            return sprintf( '%s/Resources/config/doctrine/%s', $this->path, $filename);
        } else {
            return sprintf('%s/config/doctrine/%s', $this->util->getProjectPath(), $filename);
        }
    }

    public function getEntityFilePath()
    {
        $subDirectory = $this->subDirectories ? implode('/', $this->subDirectories).'/' : '';
        $filename = sprintf('%s%sBlock.php', $subDirectory, $this->util->camelCase($this->name));

        if($this->bundle) {
            return sprintf( '%s/Entity/%s', $this->path, $filename);
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
        $filename = sprintf('%s%sBlockType.php', $subDirectory, $this->util->camelCase($this->name));

        if($this->bundle) {
            return sprintf( '%s/Form/Type/%s', $this->path, $filename);
        } else {
            return sprintf('%s/src/Form/Type/%s', $this->util->getProjectPath(), $filename);
        }
    }

    public function getTemplateFilePath()
    {
        if($this->bundle) {
            return sprintf( '%s/Resources/views/theme/block/%s.html.twig', $this->path, $this->util->kebabCase($this->name));
        } else {
            return sprintf('%s/templates/theme/block/%s.html.twig', $this->util->getProjectPath(), $this->util->kebabCase($this->name));
        }
    }

    public function getFactoryFilePath()
    {
        $subDirectory = $this->subDirectories ? implode('/', $this->subDirectories).'/' : '';
        $filename = sprintf('%s%sBlockFactory.php', $subDirectory, $this->util->camelCase($this->name));

        if($this->bundle) {
            return sprintf( '%s/Factory/%s', $this->path, $filename);
        } else {
            return sprintf('%s/src/Factory/%s', $this->util->getProjectPath(), $filename);
        }
    }

    public function getTypeFilePath()
    {
        if($this->bundle) {
            return sprintf( '%s/Block/%sBlockType.php', $this->path, $this->name);
        } else {
            return sprintf('%s/src/Block/%sBlockType.php', $this->util->getProjectPath(), $this->name);
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

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTranslationDomain()
    {
        if($this->bundle) {
            return $this->bundle->getName();
        }
        return null;
    }

    public function getApplicationName()
    {
        if($this->bundle) {
            $bundleName = $this->util->getBundleNameWithoutPostfix($this->bundle->getName());
            return $this->util->snakeCase($bundleName);
        } else {
            return $this->util->snakeCase(explode('\\', $this->namespace));
        }
    }
}
