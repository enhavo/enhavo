<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\TranslationBundle\Client\ContextProviderInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TranslationContextAliasCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $service = $container->getParameter('enhavo_translation.translation_client.context.provider');
        $container->setAlias(ContextProviderInterface::class, new Alias($service));
    }
}
