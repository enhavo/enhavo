<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle;

use Enhavo\Bundle\FrameworkBundle\Template\TemplateResolverInterface;
use Enhavo\Bundle\FrameworkBundle\Vue\RouteProvider\RouteProvider;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\LocaleResolverCompilerPass;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\RouteCollectorCompilerPass;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\TemplateExpressionLanguageCompilerPass;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\TemplateResolverPass;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\TranslationDumperCompilerPass;
use Enhavo\Bundle\FrameworkBundle\Routing\RouteCollectorInterface;
use Enhavo\Bundle\FrameworkBundle\Template\TemplateResolverAwareInterface;
use Enhavo\Bundle\FrameworkBundle\Vue\RouteProvider\VueRouteProviderTypeInterface;
use Symfony\Component\DependencyInjection\Reference;


class EnhavoFrameworkBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TemplateResolverPass());

        $container->addCompilerPass(new TranslationDumperCompilerPass());

        $container->addCompilerPass(new LocaleResolverCompilerPass());

        $container->addCompilerPass(new RouteCollectorCompilerPass());

        $container->addCompilerPass(new TemplateExpressionLanguageCompilerPass());

        $container->registerForAutoconfiguration(VueRouteProviderTypeInterface::class)
            ->addTag('enhavo_app.vue_route_provider')
        ;

        $container->registerForAutoconfiguration(RouteCollectorInterface::class)
            ->addTag('enhavo_framework.route_collector')
        ;

        $container->registerForAutoconfiguration(TemplateResolverAwareInterface::class)
            ->addMethodCall('setTemplateResolver', [new Reference(TemplateResolverInterface::class)])
        ;

        $container->addCompilerPass(
            new TypeCompilerPass('VueRouteProvider', 'enhavo_app.vue_route_provider', RouteProvider::class)
        );
    }
}
