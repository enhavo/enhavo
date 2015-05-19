<?php

namespace esperanto\AdminBundle;

use esperanto\AdminBundle\DependencyInjection\Compiler\RouteContentCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use esperanto\AdminBundle\DependencyInjection\AdminCompilerPass;

class esperantoAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AdminCompilerPass);
        $container->addCompilerPass(new RouteContentCompilerPass());
    }
}