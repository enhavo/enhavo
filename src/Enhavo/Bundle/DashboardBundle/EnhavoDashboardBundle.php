<?php

namespace Enhavo\Bundle\DashboardBundle;

use Enhavo\Bundle\DashboardBundle\Provider\Provider;
use Enhavo\Bundle\DashboardBundle\Widget\Widget;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoDashboardBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            new TypeCompilerPass('DashboardWidget', 'enhavo_dashboard.widget', Widget::class)
        );

        $container->addCompilerPass(
            new TypeCompilerPass('DashboardProvider', 'enhavo_dashboard.provider', Provider::class)
        );
    }
}
