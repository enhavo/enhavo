<?php
/**
 * RequestConfigurationCompilerPass.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TemplateResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->setAlias(TemplateResolverInterface::class, TemplateResolver::class);
    }
}
