<?php

namespace Enhavo\Bundle\TranslationBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LocaleProviderAliasCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $service = $container->getParameter('enhavo_translation.provider');

        $container->setAlias(LocaleProviderInterface::class, new Alias($service, true));
    }
}
