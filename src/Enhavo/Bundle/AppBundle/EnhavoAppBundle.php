<?php

namespace Enhavo\Bundle\AppBundle;

use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\FilesystemCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\FOSRestCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\SyliusCompilerPass;
use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\TranslationDumperPass;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoAppBundle extends Bundle
{
    const VERSION = '0.7.0';

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.column_collector', 'enhavo.column')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.block_collector', 'enhavo.block')
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
            new TypeCompilerPass('enhavo_app.batch_collector', 'enhavo.batch')
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
            new SyliusCompilerPass()
        );

        $container->addCompilerPass(
            new FilesystemCompilerPass()
        );

        $container->addCompilerPass(
            new FOSRestCompilerPass()
        );

        $container->addCompilerPass(
            new TranslationDumperPass()
        );
    }
}
