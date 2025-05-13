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
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class HostResolver implements ResolverInterface
{
    /** @var ProviderInterface */
    private $provider;

    /** @var RequestStack */
    private $requestStack;

    public function __construct(ProviderInterface $provider, RequestStack $requestStack)
    {
        $this->provider = $provider;
        $this->requestStack = $requestStack;
    }

    public function getTenant(): ?TenantInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return null;
        }

        $host = $request->getHost();
        foreach ($this->provider->getTenants() as $tenant) {
            foreach ($tenant->getDomains() as $domain) {
                if ($domain === $host) {
                    return $tenant;
                }
            }
        }

        return null;
    }
}
