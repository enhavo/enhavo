<?php

namespace Enhavo\Bundle\UserBundle;

use Enhavo\Bundle\UserBundle\DependencyInjection\Compiler\UserManagerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new UserManagerCompilerPass());
    }
}
