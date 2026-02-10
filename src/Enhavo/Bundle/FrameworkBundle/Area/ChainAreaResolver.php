<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\Area;

use Enhavo\Bundle\FrameworkBundle\Util\PriorityCollection;

class ChainAreaResolver implements AreaResolverInterface
{
    private PriorityCollection $resolvers;

    public function __construct()
    {
        $this->resolvers = new PriorityCollection();
    }

    public function addResolver(AreaResolverInterface $resolver, int $priority = 10): void
    {
        $this->resolvers->add($resolver, $priority);
    }

    public function resolve(): ?string
    {
        /** @var AreaResolverInterface $resolver */
        foreach ($this->resolvers as $resolver) {
            $key = $resolver->resolve();
            if (null !== $key) {
                return $key;
            }
        }

        return null;
    }
}
