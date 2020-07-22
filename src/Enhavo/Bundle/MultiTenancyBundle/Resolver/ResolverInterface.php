<?php


namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;

interface ResolverInterface
{
    public function getTenant(): ?TenantInterface;
}
