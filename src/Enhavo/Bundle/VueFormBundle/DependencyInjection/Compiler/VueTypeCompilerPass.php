<?php

namespace Enhavo\Bundle\VueFormBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class VueTypeCompilerPass implements CompilerPassInterface
{
    const TAG = 'vue.type';

    public function process(ContainerBuilder $container)
    {
        $targetService = $container->getDefinition(VueForm::class);
        $definitions = $container->findTaggedServiceIds(self::TAG);

        foreach ($definitions as $id => $values) {
            $priority = 100;
            foreach ($values as $valueItem) {
                if (isset($valueItem['priority'])) {
                    $priority = $valueItem['priority'];
                    break;
                }
            }

            $definition = $container->getDefinition($id);
            $definition->setPublic(true);
            $targetService->addMethodCall('registerType', [$definition->getClass(), $priority]);
        }
    }
}
