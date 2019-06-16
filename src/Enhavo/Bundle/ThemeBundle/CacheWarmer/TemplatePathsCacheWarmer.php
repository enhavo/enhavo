<?php
/**
 * Copy from symfony to fix FileLocatorInterface
 */

namespace Enhavo\Bundle\ThemeBundle\CacheWarmer;

use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinderInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;

/**
 * Computes the association between template names and their paths on the disk.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TemplatePathsCacheWarmer extends CacheWarmer
{
    protected $finder;
    protected $locator;
    private $dynamic;

    public function __construct(TemplateFinderInterface $finder, FileLocatorInterface $locator, $dynamic)
    {
        $this->finder = $finder;
        $this->locator = $locator;
        $this->dynamic = $dynamic;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        // if dynamic is enable we have dependencies to the database, but cache warm up should be independent, because
        // it could be executed before database is set up
        if($this->dynamic) {
            return;
        }

        $filesystem = new Filesystem();
        $templates = [];

        foreach ($this->finder->findAllTemplates() as $template) {
            $templates[$template->getLogicalName()] = rtrim($filesystem->makePathRelative($this->locator->locate($template), $cacheDir), '/');
        }

        $templates = str_replace("' => '", "' => __DIR__.'/", var_export($templates, true));

        $this->writeCacheFile($cacheDir.'/templates.php', sprintf("<?php return %s;\n", $templates));
    }

    /**
     * Checks whether this warmer is optional or not.
     *
     * @return bool always true
     */
    public function isOptional()
    {
        return true;
    }
}
