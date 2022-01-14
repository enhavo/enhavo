<?php

namespace Enhavo\Bundle\AppBundle;

use Enhavo\Bundle\AppBundle\Batch\Batch;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\FilesystemCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\FOSRestCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\LocaleResolverCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\SecurityCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\SyliusCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\TranslationDumperCompilerPass;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Enhavo\Bundle\AppBundle\View\View;
use Enhavo\Bundle\AppBundle\View\ViewFactoryInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoAppBundle extends Bundle
{
    const VERSION = '0.10.0';

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.column_collector', 'enhavo.column')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.button_collector', 'enhavo.button')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.action_collector', 'enhavo.action')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.viewer_collector', 'enhavo.viewer')
        );

        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('Batch', 'enhavo_app.batch', Batch::class)
        );

        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('View', 'enhavo_app.view', View::class)
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.menu_collector', 'enhavo.menu')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.filter_collector', 'enhavo.filter')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.chart_provider_collector', 'enhavo.chart_provider')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.widget_collector', 'enhavo.widget')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.init_collector', 'enhavo.init')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.toolbar_widget_collector', 'enhavo_app.toolbar_widget')
        );

        $container->addCompilerPass(
            new SyliusCompilerPass()
        );

        $container->addCompilerPass(
            new FilesystemCompilerPass()
        );

        $container->addCompilerPass(
            new TranslationDumperCompilerPass()
        );

        $container->addCompilerPass(
            new LocaleResolverCompilerPass()
        );
    }
}
