<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Locale;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;

class TenantLocaleResolver implements LocaleResolverInterface
{
    /** @var ResolverInterface */
    private $tenantResolver;

    /**
     * TenantLocaleResolver constructor.
     */
    public function __construct(ResolverInterface $tenantResolver)
    {
        $this->tenantResolver = $tenantResolver;
    }

    public function resolve()
    {
        $tenant = $this->tenantResolver->getTenant();
        if (null !== $tenant->getLocale()) {
            return $tenant->getLocale();
        }

        return null;
    }
}
