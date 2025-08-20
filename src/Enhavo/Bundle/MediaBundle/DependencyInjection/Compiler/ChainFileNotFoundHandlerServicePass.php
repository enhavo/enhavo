<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\MediaBundle\FileNotFound\ChainFileNotFoundHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ChainFileNotFoundHandlerServicePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $chainHandler = $container->findDefinition(ChainFileNotFoundHandler::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_media.file_not_found_handler') as $id => $tagAttributes) {
            if (ChainFileNotFoundHandler::class !== $id) {
                $services[$id] = new Reference($id);
            }
        }

        $chainHandler->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
