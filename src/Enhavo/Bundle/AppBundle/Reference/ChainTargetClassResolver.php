<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.03.18
 * Time: 15:19
 */

namespace Enhavo\Bundle\AppBundle\Reference;

class ChainTargetClassResolver implements TargetClassResolverInterface
{
    /**
     * @var ChainResolver[]
     */
    private $resolvers = [];

    public function resolveClass($object)
    {
        foreach($this->resolvers as $resolver)
        {
            $class = $resolver->getResolver()->resolveClass($object);
            if($class !== null) {
                return $class;
            }
        }
        return null;
    }

    public function find($id, $class)
    {
        foreach($this->resolvers as $resolver)
        {
            $entity = $resolver->getResolver()->find($id, $class);
            if($entity !== null) {
                return $entity;
            }
        }
        return null;
    }

    /**
     * @param TargetClassResolverInterface $resolver
     * @param $priority
     */
    public function addResolver(TargetClassResolverInterface $resolver, $priority)
    {
        $chainResolver = new ChainResolver();
        $chainResolver->setResolver($resolver);
        $chainResolver->setPriority($priority);
        $this->resolvers[] = $chainResolver;
        usort($this->resolvers, function(ChainResolver $a, ChainResolver $b) {
            if ($a->getPriority() == $b->getPriority()) {
                return 0;
            }
            return ($a->getPriority() < $b->getPriority()) ? -1 : 1;
        });
    }
}