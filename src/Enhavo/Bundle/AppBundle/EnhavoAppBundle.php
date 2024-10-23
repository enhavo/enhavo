<?php

namespace Enhavo\Bundle\AppBundle;

use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\LocaleResolverCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\RouteCollectorCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\TemplateExpressionLanguageCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\TemplateResolverPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\TranslationDumperCompilerPass;
use Enhavo\Bundle\AppBundle\Menu\Menu;
use Enhavo\Bundle\AppBundle\Routing\RouteCollectorInterface;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverAwareInterface;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverInterface;
use Enhavo\Bundle\AppBundle\Toolbar\ToolbarWidget;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Enhavo\Bundle\AppBundle\Vue\RouteProvider\RouteProvider;
use Enhavo\Bundle\AppBundle\Vue\RouteProvider\VueRouteProviderTypeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoAppBundle extends Bundle
{
    const VERSION = '0.13.0';

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('VueRouteProvider', 'enhavo_app.vue_route_provider', RouteProvider::class)
        );

        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('ToolbarWidget', 'enhavo_app.toolbar_widget', ToolbarWidget::class)
        );

        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('Menu', 'enhavo_app.menu', Menu::class)
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.widget_collector', 'enhavo.widget')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.init_collector', 'enhavo.init')
        );

        $container->addCompilerPass(
            new TemplateResolverPass()
        );

        $container->addCompilerPass(new TranslationDumperCompilerPass());

        $container->addCompilerPass(new LocaleResolverCompilerPass());

        $container->addCompilerPass(new RouteCollectorCompilerPass());

        $container->addCompilerPass(new TemplateExpressionLanguageCompilerPass());

        $container->registerForAutoconfiguration(VueRouteProviderTypeInterface::class)
            ->addTag('enhavo_app.vue_route_provider')
        ;

        $container->registerForAutoconfiguration(RouteCollectorInterface::class)
            ->addTag('enhavo_app.route_collector')
        ;

        $container->registerForAutoconfiguration(TemplateResolverAwareInterface::class)
            ->addMethodCall('setTemplateResolver', [new Reference(TemplateResolverInterface::class)])
        ;
    }
}
