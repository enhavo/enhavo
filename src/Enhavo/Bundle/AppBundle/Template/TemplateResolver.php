<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Template;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class TemplateResolver implements TemplateResolverInterface
{
    public const PRIORITY_LOW = 10;
    public const PRIORITY_HIGH = 100;

    private KernelInterface $kernel;
    private Filesystem $fs;
    private string $defaultPath;
    private string $themePath;

    /** @var string[] */
    private array $cache;

    /** @var TemplatePath[] */
    private array $paths = [];

    /**
     * TemplateResolver constructor.
     */
    public function __construct(
        KernelInterface $kernel,
        Filesystem $fs,
        array $templatePaths = [],
        ?string $defaultPath = null,
        ?string $themePath = null,
    ) {
        $this->fs = $fs;
        $this->kernel = $kernel;
        $this->defaultPath = $defaultPath;
        $this->themePath = $themePath;

        $this->registerPath($this->defaultPath, '', 150);
        foreach ($templatePaths as $path) {
            $this->registerPath($path['path'], $path['alias'], $path['priority']);
        }
    }

    /**
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

    public function resolve($template): string
    {
        if (isset($this->cache[$template])) {
            return $this->cache[$template];
        }

        foreach ($this->paths as $templatePath) {
            $templateFile = $this->rewritePath($templatePath, $template);

            if (null === $templateFile) {
                continue;
            }

            $this->cache[$template] = $templateFile;

            return $templateFile;
        }

        // if nothing found we return input template
        $this->cache[$template] = $template;

        return $template;
    }

    private function rewritePath(TemplatePath $templatePath, string $template)
    {
        if (preg_match('/^@/', $templatePath->getPath())) {
            $templateFile = sprintf('%s/%s', $templatePath->getPath(), $template);
            try {
                $this->kernel->locateResource($templateFile);
            } catch (\InvalidArgumentException $e) {
                return null;
            }

            $template = ltrim($template, '/');

            return sprintf('@%s/%s', $templatePath->getAlias(), $template);
        }
        $templateFile = sprintf('%s/%s', $templatePath->getPath(), $template);
        if ($this->fs->exists($templateFile)) {
            if ($templatePath->getPath() === $this->defaultPath) {
                return $template;
            }

            return sprintf('@%s/%s', $templatePath->getAlias(), $template);
        }

        return null;
    }

    private function isSubDir($dir, $subDir)
    {
        $dir = realpath($dir);
        $subDir = realpath($subDir);

        if (null === $dir || null === $subDir) {
            return false;
        }

        $dir = explode('/', $dir);
        $subDir = explode('/', $subDir);

        for ($i = 0; $i < count($dir); ++$i) {
            if (!isset($subDir[$i]) || $dir[$i] !== $subDir[$i]) {
                return false;
            }
        }

        return true;
    }
}
