<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Type;

use Enhavo\Bundle\AppBundle\Exception\AliasRequiredException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author gseidel
 */
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

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->collectorServiceId)) {
            return null;
        }

        $definition = $container->getDefinition(
            $this->collectorServiceId
        );

        $taggedServices = $container->findTaggedServiceIds(
            $this->tagName
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                if (isset($attributes['alias'])) {
                    $definition->addMethodCall(
                        'add',
                        [$attributes['alias'], $id]
                    );
                } else {
                    $definition = $container->findDefinition($id);
                    if (!$definition->isAbstract()) {
                        throw new AliasRequiredException(sprintf('alias required for %s', $id));
                    }
                }
            }
        }
    }
}
