<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-06
 * Time: 18:15
 */

namespace Enhavo\Bundle\ThemeBundle\Locator;

use Enhavo\Bundle\ThemeBundle\Exception\TemplateMapException;
use Enhavo\Bundle\ThemeBundle\Template\TemplateMapper;
use Enhavo\Bundle\ThemeBundle\ThemeLoader\ThemeLoaderInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

class ThemeTemplateLocator implements FileLocatorInterface
{
    private $cache;

    /**
     * @var FileLocatorInterface
     */
    private $locator;

    /**
     * @var TemplateMapper
     */
    private $templateMapper;

    /**
     * @var string[]
     */
    private $cacheHits = [];

    /**
     * ThemeTemplateLocator constructor.
     *
     * @param FileLocatorInterface $locator
     * @param string|null $cacheDir
     * @param ThemeLoaderInterface $loader
     */
    public function __construct(FileLocatorInterface $locator, string $cacheDir, TemplateMapper $templateMapper)
    {
        if (null !== $cacheDir && file_exists($cache = $cacheDir.'/templates.php')) {
            $this->cache = require $cache;
        }

        $this->locator = $locator;
        $this->templateMapper = $templateMapper;
    }

    /**
     * @param TemplateReferenceInterface $template
     * @return string
     */
    private function getCacheKey(TemplateReferenceInterface $template)
    {
        return $template->getLogicalName();
    }

    /**
     * Returns a full path for a given file.
     *
     * @param TemplateReferenceInterface $template    A template
     * @param string                     $currentPath Unused
     * @param bool                       $first       Unused
     *
     * @return string The full path for the file
     *
     * @throws \InvalidArgumentException When the template is not an instance of TemplateReferenceInterface
     * @throws \InvalidArgumentException When the template file can not be found
     */
    public function locate($template, $currentPath = null, $first = true)
    {
        if (!$template instanceof TemplateReferenceInterface) {
            throw new \InvalidArgumentException('The template must be an instance of TemplateReferenceInterface.');
        }

        $key = $this->getCacheKey($template);

        if (isset($this->cacheHits[$key])) {
            return $this->cacheHits[$key];
        }
        if (isset($this->cache[$key])) {
            return $this->cacheHits[$key] = realpath($this->cache[$key]) ?: $this->cache[$key];
        }

        try {
            $templateFile = $this->locateTemplate($template);
            //$templateFile = $this->locator->locate($template->getPath());
            $this->cacheHits[$key] = $templateFile;
            return $this->cacheHits[$key];
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException(sprintf('Unable to find template "%s" : "%s".', $template, $e->getMessage()), 0, $e);
        }
    }

    private function locateTemplate($template)
    {
        try {
            $mapTemplate = $this->templateMapper->map($template);
            return $this->locator->locate($mapTemplate->getPath());
        } catch(\InvalidArgumentException $e) {
            if (isset($mapTemplate) && $mapTemplate !== $template) {
                throw new TemplateMapException(sprintf('Unable to map template template "%s" : "%s".', $template, $e->getMessage()));
            }
            throw $e;
        }
    }
}
