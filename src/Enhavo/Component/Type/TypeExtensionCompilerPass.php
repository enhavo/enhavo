<?php

/**
 * AbstractCollectorPass.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Component\Type;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TypeExtensionCompilerPass implements CompilerPassInterface
{
    public function __construct(
        private string $namespace,
        private string $tagName
    )
    {
    }

    /**
     * @inheritdoc
     */
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
                array($tagServiceDefinition->getClass() ?: $id, $id, $priority)
            );
        }
    }
}
