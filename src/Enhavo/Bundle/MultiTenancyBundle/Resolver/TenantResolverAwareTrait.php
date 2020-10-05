<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;

trait TenantResolverAwareTrait
{
    /**
     * @var ResolverInterface
     */
    protected $tenantResolver;

    public function setTenantResolver(ResolverInterface $tenantResolver = null)
    {
        $this->tenantResolver = $tenantResolver;
    }

    public function getTenant(): ?TenantInterface
    {
        return $this->tenantResolver === null ? null : $this->tenantResolver->getTenant();
    }

    public function getTenantKey(): ?string
    {
        if ($this->tenantResolver === null) {
            return null;
        }
        if ($this->tenantResolver->getTenant() === null) {
            return null;
        }
        return $this->tenantResolver->getTenant()->getKey();
    }
}
