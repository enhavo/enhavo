<?php

namespace Enhavo\Bundle\SettingBundle;

use Enhavo\Bundle\SettingBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoSettingBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
//        $container->addCompilerPass(new ConfigCompilerPass($this->kernel));
    }
}
