<?php

declare(strict_types=1);

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler;


use Enhavo\Bundle\ResourceBundle\Form\FormNormalizerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Enhavo\Bundle\ResourceBundle\Form\VueFormNormalizer;
use Symfony\Component\DependencyInjection\Reference;

class FormNormalizerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $this->addVueFormNormalizerDefinition($container);
        $this->setAlias($container);
    }

    private function setAlias(ContainerBuilder $container)
    {
        $normalizerService = $container->getParameter('enhavo_resource.form.normalizer');
        $container->setAlias(FormNormalizerInterface::class, $normalizerService);
    }

    private function addVueFormNormalizerDefinition(ContainerBuilder $container): void
    {
        if (class_exists('Enhavo\Bundle\VueFormBundle\Form\VueForm')) {
            $definition = new Definition(VueFormNormalizer::class);
            $definition->setClass(VueFormNormalizer::class);
            $definition->addArgument(new Reference('Enhavo\Bundle\VueFormBundle\Form\VueForm'));
            $container->addDefinitions([$definition]);
        }
    }
}
