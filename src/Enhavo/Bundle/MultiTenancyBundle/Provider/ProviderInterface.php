<?php


namespace Enhavo\Bundle\MultiTenancyBundle\Provider;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;

interface ProviderInterface
{
    /**
     * @return TenantInterface[]
     */
    public function getTenants(): array;
}
