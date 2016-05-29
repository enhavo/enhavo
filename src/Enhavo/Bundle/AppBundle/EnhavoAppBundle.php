<?php

namespace Enhavo\Bundle\AppBundle;

use Enhavo\Bundle\AppBundle\DependencyInjection\Compiler\RouteContentCompilerPass;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoAppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RouteContentCompilerPass());

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.table_widget_collector', 'enhavo.table_widget')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.block_collector', 'enhavo.block')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.button_collector', 'enhavo.button')
        );
    }
}