<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 23:09
 */

namespace Enhavo\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ThemeBundle\Theme\ThemeWebpackBuildResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EnhavoCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteTemplateManager($container);
    }

    private function overwriteTemplateManager(ContainerBuilder $container)
    {
        $container->getDefinition('enhavo_app.template.manager')
            ->replaceArgument(2, new Reference(ThemeWebpackBuildResolver::class));
    }
}
