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

trait TenantResolverAwareTrait
{
    /**
     * @var ResolverInterface
     */
    protected $tenantResolver;

    public function setTenantResolver(?ResolverInterface $tenantResolver = null)
    {
        $this->tenantResolver = $tenantResolver;
    }

    public function getTenant(): ?TenantInterface
    {
        return null === $this->tenantResolver ? null : $this->tenantResolver->getTenant();
    }

    public function getTenantKey(): ?string
    {
        if (null === $this->tenantResolver) {
            return null;
        }
        if (null === $this->tenantResolver->getTenant()) {
            return null;
        }

        return $this->tenantResolver->getTenant()->getKey();
    }
}
