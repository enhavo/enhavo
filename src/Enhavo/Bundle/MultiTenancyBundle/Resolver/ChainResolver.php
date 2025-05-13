<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;

class ChainResolver implements ResolverInterface
{
    /** @var ResolverEntry[] */
    private $entries = [];

    public function addResolver(ResolverInterface $resolver, $priority = 100)
    {
        $this->entries[] = new ResolverEntry($resolver, $priority);
        usort($this->entries, function (ResolverEntry $entryOne, ResolverEntry $entryTwo) {
            return $entryTwo->getPriority() - $entryOne->getPriority();
        });
    }

    public function getTenant(): ?TenantInterface
    {
        foreach ($this->entries as $entry) {
            $tenant = $entry->getResolver()->getTenant();
            if (null !== $tenant) {
                return $tenant;
            }
        }

        return null;
    }
}
