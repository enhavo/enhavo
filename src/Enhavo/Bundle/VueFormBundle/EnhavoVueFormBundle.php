<?php

namespace Enhavo\Bundle\VueFormBundle;

use Enhavo\Bundle\VueFormBundle\DependencyInjection\Compiler\VueTypeCompilerPass;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoVueFormBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new VueTypeCompilerPass());
        $container->registerForAutoconfiguration(VueTypeInterface::class)->addTag('vue.type');
    }
}
