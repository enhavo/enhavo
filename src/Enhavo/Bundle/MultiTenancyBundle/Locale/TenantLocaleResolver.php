<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Locale;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;

class TenantLocaleResolver implements LocaleResolverInterface
{
    /** @var ResolverInterface */
    private $tenantResolver;

    /**
     * TenantLocaleResolver constructor.
     * @param ResolverInterface $tenantResolver
     */
    public function __construct(ResolverInterface $tenantResolver)
    {
        $this->tenantResolver = $tenantResolver;
    }

    public function resolve()
    {
        $tenant = $this->tenantResolver->getTenant();
        if ($tenant->getLocale() !== null) {
            return $tenant->getLocale();
        }

        return null;
    }
}
