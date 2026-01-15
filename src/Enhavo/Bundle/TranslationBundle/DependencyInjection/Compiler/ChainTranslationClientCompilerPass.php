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

use Enhavo\Bundle\TranslationBundle\Client\ChainTranslationClient;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ChainTranslationClientCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $clients = $container->getParameter('enhavo_translation.translation_client.chain.clients');
        $chain = $container->getDefinition(ChainTranslationClient::class);

        foreach ($clients as $client) {
            $definition = $container->getDefinition($client);
            $chain->addMethodCall('addClient', [$definition]);
        }
    }
}
