<?php

namespace Enhavo\Bundle\ThemeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoThemeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_theme.theme_widget_collector', 'enhavo.theme_widget')
        );
    }
}
