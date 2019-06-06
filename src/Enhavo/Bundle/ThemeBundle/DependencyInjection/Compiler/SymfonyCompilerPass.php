<?php
/**
 * SymfonyCompilerPass.php
 *
 * @since 06/06/19
 * @author gseidel
 */

namespace Enhavo\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ThemeBundle\Locator\ThemeTemplateLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymfonyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteTemplateLocator($container);
    }

    protected function overwriteTemplateLocator(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('templating.locator');
        $definition->setClass(ThemeTemplateLocator::class);
        $definition->addArgument($container->getDefinition('enhavo_theme.theme_loader.config'));
    }
}
