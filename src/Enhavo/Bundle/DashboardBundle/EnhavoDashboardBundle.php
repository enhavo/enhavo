<?php

namespace Enhavo\Bundle\DashboardBundle;

use Enhavo\Bundle\DashboardBundle\Widget\Widget;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoDashboardBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('DashboardWidget', 'enhavo_dashboard.widget', Widget::class)
        );
    }
}
