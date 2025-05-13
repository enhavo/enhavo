<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\DependencyInjection\CompilerPass;

use Enhavo\Bundle\ApiBundle\Normalizer\DataNormalizer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DataNormalizerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $dataNormalizer = $container->findDefinition(DataNormalizer::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_api.data_normalizer') as $id => $tagAttributes) {
            $priority = null;
            foreach ($tagAttributes as $attributes) {
                if (isset($attributes['priority'])) {
                    $priority = intval($attributes['priority']);
                }
            }
            $services[$id] = new Reference($id);
            $definition = $container->findDefinition($id);
            $arguments = [$definition->getClass()];
            if (null !== $priority) {
                $arguments[] = $priority;
            }
            $dataNormalizer->addMethodCall('register', $arguments);
        }

        $dataNormalizer->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
