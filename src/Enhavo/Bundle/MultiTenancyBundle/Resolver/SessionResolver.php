<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionResolver implements ResolverInterface
{
    /** @var ProviderInterface */
    private $provider;

    /** @var RequestStack */
    private $requestStack;

    /** @var string|null */
    private $routePrefixOnly;

    /** @var string */
    private $sessionKey;

    public function __construct(ProviderInterface $provider, RequestStack $requestStack, $routePrefixOnly = null, $sessionKey = 'tenant')
    {
        $this->provider = $provider;
        $this->requestStack = $requestStack;
        $this->routePrefixOnly = $routePrefixOnly;
        $this->sessionKey = $sessionKey;
    }

    public function getTenant(): ?TenantInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($this->routePrefixOnly !== null && $this->routePrefixOnly !== '') {
            $path = $request->getPathInfo();
            if (strpos($path, $this->routePrefixOnly) !== 0) {
                return null;
            }
        }

        if(!$request->getSession()->has($this->sessionKey)) {
            return null;
        }

        $tenantKey = $request->getSession()->get($this->sessionKey);

        foreach ($this->provider->getTenants() as $tenant) {
            $keys[] = $tenant->getKey();
            if ($tenant->getKey() === $tenantKey) {
                return $tenant;
            }
        }

        return null;
    }
}
