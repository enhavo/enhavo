<?php

namespace Enhavo\Bundle\AppBundle\Area;

use Laminas\Stdlib\PriorityQueue;

class ChainAreaResolver implements AreaResolverInterface
{
    private PriorityQueue $resolvers;

    public function __construct()
    {
        $this->resolvers = new PriorityQueue();
    }

    public function addResolver(AreaResolverInterface $resolver, int $priority = 10): void
    {
        $this->resolvers->insert($resolver, $priority);
    }

    public function resolve(): ?string
    {
        /** @var AreaResolverInterface $resolver */
        foreach ($this->resolvers as $resolver) {
            $key = $resolver->resolve();
            if ($key !== null) {
                return $key;
            }
        }
        return null;
    }
}
