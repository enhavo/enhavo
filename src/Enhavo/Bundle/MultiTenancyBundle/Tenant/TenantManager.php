<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.07.18
 * Time: 23:06
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Tenant;

use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;

class TenantManager
{
    /** @var ResolverInterface */
    private $resolver;

    /** @var ProviderInterface */
    private $provider;

    /**
     * TenantManager constructor.
     * @param ResolverInterface $resolver
     * @param ProviderInterface $provider
     */
    public function __construct(ResolverInterface $resolver, ProviderInterface $provider)
    {
        $this->resolver = $resolver;
        $this->provider = $provider;
    }

    public function getTenant()
    {
        return $this->resolver->getTenant();
    }

    public function getTenants()
    {
        return $this->provider->getTenants();
    }
}
