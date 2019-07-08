<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 15:58
 */

namespace Enhavo\Bundle\AppBundle\Template;

use Enhavo\Bundle\AppBundle\Exception\TemplateNotFoundException;
use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class TemplateManager
{
    const PRIORITY_LOW = 10;
    const PRIORITY_HIGH = 100;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var TemplatePath[]
     */
    private $paths = [];

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string[]
     */
    private $cache;

    /**
     * @var string
     */
    private $defaultPath;

    /**
     * @var $resolver
     */
    private $resolver;

    /**
     * TemplateManager constructor.
     * @param KernelInterface $kernel
     * @param Filesystem $fs
     * @param WebpackBuildResolverInterface $resolver
     * @param array $templatePaths
     * @param string $defaultPath
     */
    public function __construct(KernelInterface $kernel, Filesystem $fs, WebpackBuildResolverInterface $resolver, array $templatePaths = [], string $defaultPath = null)
    {
        $this->fs = $fs;
        $this->kernel = $kernel;
        $this->defaultPath = $defaultPath;
        $this->resolver = $resolver;

        foreach($templatePaths as $path) {
            $this->registerPath($path['path'], $path['priority']);
        }
    }

    /**
     * @param $path
     * @param int $priority
     */
    public function registerPath($path, $priority = self::PRIORITY_LOW)
    {
        $templatePath = new TemplatePath();
        $templatePath->setPriority($priority);
        $templatePath->setPath($path);

        $this->paths[] = $templatePath;

        usort($this->paths, function (TemplatePath $a, TemplatePath $b) {
            return $b->getPriority() - $a->getPriority();
        });
    }

    /**
     * @param $template
     * @return string
     * @throws TemplateNotFoundException
     */
    public function getTemplate($template)
    {
        if(isset($this->cache[$template])) {
            return $this->cache[$template];
        }

        foreach($this->paths as $path) {
            $templatePath = sprintf('%s/%s', $path->getPath(), $template);
            if(preg_match('/^@/', $templatePath)) {
                try {
                    if($this->kernel->locateResource($templatePath)) {
                        $this->cache[$template] = $templatePath;
                        return $templatePath;
                    }
                } catch(\InvalidArgumentException $e) {
                    continue;
                }
            } elseif($this->defaultPath) {
                if($path->getPath()) {
                    $filepath = sprintf('%s/%s/%s', $this->defaultPath, $path->getPath(), $template);
                } else {
                    $filepath = sprintf('%s/%s', $this->defaultPath, $template);
                }
                if($this->fs->exists($filepath)) {
                    $this->cache[$template] = $templatePath;
                    return $templatePath;
                }
            }
        }

        throw new TemplateNotFoundException(sprintf('The template "%s" could not be found', $template));
    }

    public function getWebpackBuild()
    {
        return $this->resolver->resolve();
    }
}
