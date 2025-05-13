<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle;

use Enhavo\Bundle\UserBundle\DependencyInjection\Compiler\ConfigKeyProviderCompilerPass;
use Enhavo\Bundle\UserBundle\DependencyInjection\Compiler\ErrorMessageCompilerPass;
use Enhavo\Bundle\UserBundle\DependencyInjection\Compiler\UserManagerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoUserBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new UserManagerCompilerPass());
        $container->addCompilerPass(new ConfigKeyProviderCompilerPass());
        $container->addCompilerPass(new ErrorMessageCompilerPass());
    }
}
