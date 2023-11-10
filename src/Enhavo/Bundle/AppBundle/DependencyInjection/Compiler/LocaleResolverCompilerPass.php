<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\AppBundle\EventListener\LocaleSubscriber;
use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LocaleResolverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resolver = $container->getParameter('enhavo_app.locale_resolver');
        $container->setAlias('enhavo_app.locale_resolver', new Alias($resolver, true));
        $container->setAlias(LocaleResolverInterface::class, new Alias($resolver, true));
    }
}
