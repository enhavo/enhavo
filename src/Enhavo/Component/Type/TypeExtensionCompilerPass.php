<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TypeExtensionCompilerPass implements CompilerPassInterface
{
    public function __construct(
        private string $namespace,
        private string $tagName,
    ) {
    }

    public function process(ContainerBuilder $container)
    {
        $registryDefinitionId = sprintf('%s[%s]', RegistryInterface::class, $this->namespace);
        $registryDefinition = $container->getDefinition($registryDefinitionId);

        $taggedServices = $container->findTaggedServiceIds($this->tagName);

        foreach ($taggedServices as $id => $tagAttributes) {
            $priority = 10;
            foreach ($tagAttributes as $tagAttribute) {
                if (isset($tagAttribute['priority'])) {
                    $priority = $tagAttribute['priority'];
                    break;
                }
            }

            $tagServiceDefinition = $container->getDefinition($id);
            $tagServiceDefinition->setPublic(true);
            $registryDefinition->addMethodCall(
                'registerExtension',
                [$tagServiceDefinition->getClass() ?: $id, $id, $priority]
            );
        }
    }
}
