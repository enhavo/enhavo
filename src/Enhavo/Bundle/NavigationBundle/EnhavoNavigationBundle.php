<?php

namespace Enhavo\Bundle\NavigationBundle;

use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoNavigationBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_navigation.item_collector', 'enhavo_navigation.item')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_navigation.voter_collector', 'enhavo_navigation.voter')
        );
    }
}
