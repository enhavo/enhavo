<?php


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
            if($tenant !== null) {
                return $tenant;
            }
        }
        return null;
    }
}
