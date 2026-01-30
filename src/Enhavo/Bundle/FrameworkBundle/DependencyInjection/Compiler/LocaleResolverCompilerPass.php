<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\FrameworkBundle\Locale\LocaleResolverInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LocaleResolverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $resolver = $container->getParameter('enhavo_framework.locale_resolver');
        $container->setAlias('enhavo_framework.locale_resolver', new Alias($resolver, true));
        $container->setAlias(LocaleResolverInterface::class, new Alias($resolver, true));
    }
}
