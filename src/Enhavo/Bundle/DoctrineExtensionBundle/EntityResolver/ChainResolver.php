<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.03.18
 * Time: 15:19
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver;

use Enhavo\Bundle\DoctrineExtensionBundle\Exception\ResolveException;

class ChainResolver implements EntityResolverInterface
{
    /**
     * @var ChainResolverEntry[]
     */
    private $resolvers = [];

    public function getName($entity): string
    {
        foreach($this->resolvers as $resolver)
        {
            try {
                return $resolver->getResolver()->getName($entity);
            } catch (ResolveException $e) {
                continue;
            }
        }
        throw ResolveException::invalidEntity($entity);
    }

    public function getEntity(int $id, string $name): ?object
    {
        foreach($this->resolvers as $resolver)
        {
            $entity = $resolver->getResolver()->getEntity($id, $name);
            if($entity !== null) {
                return $entity;
            }
        }
        return null;
    }

    /**
     * @param EntityResolverInterface $resolver
     * @param int $priority Higher number will be resolved more early
     */
    public function addResolver(EntityResolverInterface $resolver, int $priority = 0)
    {
        $entry = new ChainResolverEntry($resolver, $priority);
        $this->resolvers[] = $entry;
        usort($this->resolvers, function(ChainResolverEntry $a, ChainResolverEntry $b) {
            if ($a->getPriority() == $b->getPriority()) {
                return 0;
            }
            return ($a->getPriority() < $b->getPriority()) ? 1 : -1;
        });
    }
}
