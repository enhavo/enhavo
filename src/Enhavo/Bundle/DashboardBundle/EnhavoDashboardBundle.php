<?php

namespace Enhavo\Bundle\DashboardBundle;

use Enhavo\Bundle\DashboardBundle\Dashboard\DashboardWidget;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoDashboardBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            new TypeCompilerPass('DashboardWidget', 'enhavo_dashboard.widget', DashboardWidget::class)
        );
    }
}
