<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;

class AssignResolver implements ResolverInterface
{
    /** @var ProviderInterface */
    private $provider;

    /**
     * @var string|null
     */
    private $assignedKey = null;

    /**
     * AssignResolver constructor.
     *
     * @param ProviderInterface $provider
     * @param string|null $initialAssigned
     */
    public function __construct(ProviderInterface $provider, $initialAssigned = null)
    {
        $this->provider = $provider;
        $this->assignedKey = $initialAssigned;
    }

    /**
     * @param string|null $key
     */
    public function assign(?string $key)
    {
        $this->assignedKey = $key;
    }

    public function getTenant(): ?TenantInterface
    {
        if ($this->assignedKey === null) {
            return null;
        }
        foreach ($this->provider->getTenants() as $tenant) {
            if ($tenant->getKey() === $this->assignedKey) {
                return $tenant;
            }
        }
        return null;
    }
}
