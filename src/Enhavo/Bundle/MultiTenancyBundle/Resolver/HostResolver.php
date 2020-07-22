<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.08.18
 * Time: 18:23
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

        if($request === null) {
            return null;
        }

        $host = $request->getHost();
        foreach ($this->provider->getTenants() as $tenant) {
            foreach($tenant->getDomains() as $domain) {
                if($domain === $host) {
                    return $tenant;
                }
            }
        }

        return null;
    }
}
