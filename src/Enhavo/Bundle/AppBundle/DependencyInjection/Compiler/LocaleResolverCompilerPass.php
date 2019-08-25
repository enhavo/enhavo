<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\AppBundle\EventListener\LocaleSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LocaleResolverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resolver = $container->getParameter('enhavo_app.locale_resolver');
        $container->getDefinition(LocaleSubscriber::class)->replaceArgument(0, $container->getDefinition($resolver));
    }
}
