<?php

namespace Enhavo\Bundle\VueFormBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class VueTypeCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        $extension = $container->getDefinition(VueForm::class);

        $taggedServices = $container->findTaggedServiceIds('vue.type');

        foreach ($taggedServices as $id => $tagAttributes) {
            $tagServiceDefinition = $container->getDefinition($id);
            $tagServiceDefinition->setPublic(true);
            $extension->addMethodCall(
                'register',
                array($tagServiceDefinition->getClass() ? $tagServiceDefinition->getClass(): $id)
            );
        }
    }
}
