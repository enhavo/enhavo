<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

interface TenantResolverAwareInterface
{
    public function setTenantResolver(ResolverInterface $tenantResolver = null);
}
