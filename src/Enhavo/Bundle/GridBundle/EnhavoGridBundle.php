<?php

namespace Enhavo\Bundle\GridBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;

class EnhavoGridBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_grid.item_configuration_collector', 'enhavo.grid_item')
        );
    }
}
