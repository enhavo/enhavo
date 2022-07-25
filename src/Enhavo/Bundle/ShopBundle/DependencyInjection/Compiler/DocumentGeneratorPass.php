<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DocumentGeneratorPass implements CompilerPassInterface
{
    const TAG = 'enhavo_shop.document_generator';

    public function process(ContainerBuilder $container)
    {
        $documentManagerAlias = $container->getAlias('enhavo_shop.document_manager');
        $documentManager = $container->getDefinition($documentManagerAlias);
        $definitions = $container->findTaggedServiceIds(self::TAG);

        foreach ($definitions as $id => $values) {
            $name = null;
            foreach ($values as $valueItem) {
                if (isset($valueItem['alias'])) {
                    $name = $valueItem['alias'];
                    break;
                }
            }

            if ($name) {
                $definition = $container->getDefinition($id);
                $documentManager->addMethodCall('addGenerator', [$name, $definition]);
            }
        }
    }
}
