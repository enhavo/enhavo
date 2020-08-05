<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 18.11.17
 * Time: 18:35
 */

namespace Enhavo\Bundle\Enhavo\MultiTenancyBundle;

use Enhavo\Bundle\MultiTenancyBundle\DependencyInjection\CompilerPass\SyliusCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoMultiTenancyBundle extends Bundle
{
    public function  build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SyliusCompilerPass());
    }
}
