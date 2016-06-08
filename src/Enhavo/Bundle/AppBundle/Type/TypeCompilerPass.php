<?php

/**
 * AbstractCollectorPass.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Type;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TypeCompilerPass implements CompilerPassInterface
{
    /**
     * The service id of the collection, who will collect all the types.
     *
     * @var string
     */
    protected $collectorServiceId;

    /**
     * The name of the tag, which the collector should collect.
     *
     * @var string
     */
    protected $tagName;

    public function __construct($collectorServiceId, $tagName)
    {
        $this->collectorServiceId = $collectorServiceId;
        $this->tagName = $tagName;
    }

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->collectorServiceId)) {
            return;
        }

        $definition = $container->getDefinition(
            $this->collectorServiceId
        );

        $taggedServices = $container->findTaggedServiceIds(
            $this->tagName
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'add',
                    array(new Reference($id))
                );
            }
        }
    }
}