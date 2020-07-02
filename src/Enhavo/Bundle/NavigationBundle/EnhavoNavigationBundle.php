<?php

namespace Enhavo\Bundle\NavigationBundle;

use Enhavo\Bundle\NavigationBundle\NavItem\NavItem;
use Enhavo\Bundle\NavigationBundle\Voter\Voter;
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

        $container->addCompilerPass(
            new TypeCompilerPass('NavigationVoter', 'enhavo_navigation.voter', Voter::class)
        );
    }
}
