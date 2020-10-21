<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.07.18
 * Time: 23:06
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Tenant;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;

class TenantManager
{
    /** @var ResolverInterface */
    private $resolver;

    /** @var ProviderInterface */
    private $provider;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * TenantManager constructor.
     * @param ResolverInterface $resolver
     * @param ProviderInterface $provider
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ResolverInterface $resolver, ProviderInterface $provider, EntityManagerInterface $entityManager)
    {
        $this->resolver = $resolver;
        $this->provider = $provider;
        $this->entityManager = $entityManager;
    }

    public function getTenant($key = null)
    {
        if ($key) {
            foreach($this->provider->getTenants() as $tenant) {
                if ($tenant->getKey() == $key) {
                    return $tenant;
                }
            }
            return null;
        }
        return $this->resolver->getTenant();
    }

    public function getTenants()
    {
        return $this->provider->getTenants();
    }

    public function disableDoctrineFilter()
    {
        $this->entityManager->getFilters()->disable('tenant');
    }

    public function enableDoctrineFilter()
    {
        $this->entityManager->getFilters()->enable('tenant');
    }
}
