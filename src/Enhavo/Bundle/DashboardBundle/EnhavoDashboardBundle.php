<?php

namespace Enhavo\Bundle\DashboardBundle;

use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoDashboardBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_dashboard.statistic_collector', 'enhavo.statistic')
        );
    }
}
