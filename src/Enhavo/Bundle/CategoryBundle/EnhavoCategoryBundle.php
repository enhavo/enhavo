<?php

namespace Enhavo\Bundle\CategoryBundle;

use Enhavo\Bundle\CategoryBundle\DependencyInjection\Compiler\CollectionRepositoryCompiler;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoCategoryBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CollectionRepositoryCompiler());
    }
}
