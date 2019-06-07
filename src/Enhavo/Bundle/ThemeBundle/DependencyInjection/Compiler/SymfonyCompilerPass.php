<?php
/**
 * SymfonyCompilerPass.php
 *
 * @since 06/06/19
 * @author gseidel
 */

namespace Enhavo\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ThemeBundle\Locator\ThemeTemplateLocator;
use Enhavo\Bundle\ThemeBundle\CacheWarmer\TemplatePathsCacheWarmer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymfonyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteTemplateLocator($container);
        $this->overwriteCacheWarmer($container);
    }

    protected function overwriteTemplateLocator(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('templating.locator');
        $definition->setClass(ThemeTemplateLocator::class);
        $definition->addArgument($container->getDefinition('enhavo_theme.theme_loader.config'));
    }

    protected function overwriteCacheWarmer(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('templating.cache_warmer.template_paths');
        $definition->setClass(TemplatePathsCacheWarmer::class);
    }
}
