<?php

namespace Enhavo\Bundle\NavigationBundle;

use Enhavo\Bundle\NavigationBundle\DependencyInjection\Compiler\VoterCompilerPass;
use Enhavo\Bundle\NavigationBundle\NavItem\NavItem;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoNavigationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new TypeCompilerPass('NavItem', 'enhavo_navigation.nav_item', NavItem::class)
        );

        $container->addCompilerPass(new VoterCompilerPass());
    }
}
