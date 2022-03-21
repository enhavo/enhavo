<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 15:58
 */

namespace Enhavo\Bundle\AppBundle\Template;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class TemplateManager
{
    const PRIORITY_LOW = 10;
    const PRIORITY_HIGH = 100;

    private KernelInterface $kernel;
    private Filesystem $fs;
    private string $defaultPath;
    private WebpackBuildResolverInterface $resolver;
    private string $themePath;

    /** @var string[] */
    private array $cache;

    /** @var TemplatePath[] */
    private array $paths = [];

    /**
     * TemplateManager constructor.
     * @param KernelInterface $kernel
     * @param Filesystem $fs
     * @param WebpackBuildResolverInterface $resolver
     * @param array $templatePaths
     * @param string $defaultPath
     * @param string $themePath
     */
    public function __construct(
        KernelInterface $kernel,
        Filesystem $fs,
        WebpackBuildResolverInterface $resolver,
        array $templatePaths = [],
        string $defaultPath = null,
        string $themePath = null
    ) {
        $this->fs = $fs;
        $this->kernel = $kernel;
        $this->defaultPath = $defaultPath;
        $this->resolver = $resolver;
        $this->themePath = $themePath;

        foreach($templatePaths as $path) {
            $this->registerPath($path['path'], $path['alias'], $path['priority']);
        }
    }

    /**
     * @param $path
     * @param int $priority
     */
    public function registerPath($path, $alias, $priority = self::PRIORITY_LOW)
    {
        $templatePath = new TemplatePath();
        $templatePath->setPriority($priority);
        $templatePath->setPath($path);
        $templatePath->setAlias($alias);

        $this->paths[] = $templatePath;

        usort($this->paths, function (TemplatePath $a, TemplatePath $b) {
            return $b->getPriority() - $a->getPriority();
        });
    }

    /**
     * @param $template
     * @return string
     */
    public function getTemplate($template)
    {
        if (isset($this->cache[$template])) {
            return $this->cache[$template];
        }

        foreach($this->paths as $templatePath) {
            $templateFile = $this->rewritePath($templatePath, $template);

            if($templateFile === null) {
                continue;
            }

            $this->cache[$template] = $templateFile;
            return $templateFile;
        }

        // if nothing found we return input template
        $this->cache[$template] = $template;
        return $template;
    }

    public function getWebpackBuild()
    {
        return $this->resolver->resolve();
    }

    private function rewritePath(TemplatePath $templatePath, string $template)
    {
        if (preg_match('/^@/', $templatePath->getPath())) {
            $templateFile = sprintf('%s/%s', $templatePath->getPath(), $template);
            try {
                $this->kernel->locateResource($templateFile);
            } catch(\InvalidArgumentException $e) {
                return null;
            }
            return sprintf('@%s/%s', $templatePath->getAlias(), $template);
//        } elseif ($this->isSubDir($this->themePath, $templatePath->getPath())) {
//            $templateFile = sprintf('%s/%s', $templatePath->getPath(), $template);
//            if ($this->fs->exists($templateFile)) {
//                return sprintf('@theme/%s/templates/%s', basename(realpath($templatePath->getPath().'/..')), $template);
//            }
        } else {
            $templateFile = sprintf('%s/%s', $templatePath->getPath(), $template);
            if ($this->fs->exists($templateFile)) {
                return sprintf('@%s/%s', $templatePath->getAlias(), $template);
            }
        }

        return null;
    }

    private function isSubDir($dir, $subDir)
    {
        $dir = realpath($dir);
        $subDir = realpath($subDir);

        if($dir === null || $subDir === null) {
            return false;
        }

        $dir = explode('/', $dir);
        $subDir = explode('/', $subDir);

        for ($i = 0; $i < count($dir); $i++) {
            if (!isset($subDir[$i]) || $dir[$i] !== $subDir[$i]) {
                return false;
            }
        }

        return true;
    }
}
