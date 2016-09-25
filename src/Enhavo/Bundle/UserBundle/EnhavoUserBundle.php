<?php

namespace Enhavo\Bundle\UserBundle;

use Enhavo\Bundle\UserBundle\DependencyInjection\Compiler\FOSUserCompilerPass;
use Enhavo\Bundle\UserBundle\DependencyInjection\Compiler\SecurityCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }

    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new FOSUserCompilerPass()
        );

        $container->addCompilerPass(
            new SecurityCompilerPass()
        );
    }
}