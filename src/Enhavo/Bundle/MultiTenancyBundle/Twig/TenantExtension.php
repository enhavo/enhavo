<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Twig;

use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;
use Enhavo\Bundle\MultiTenancyBundle\Tenant\TenantManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TenantExtension extends AbstractExtension
{
    /** @var TenantManager */
    private $manager;

    /**
     * TenantExtension constructor.
     * @param TenantManager $manager
     */
    public function __construct(TenantManager $manager)
    {
        $this->manager = $manager;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('tenant', array($this, 'getTenant')),
            new TwigFunction('tenants', array($this, 'getTenants')),
        );
    }

    /**
     * @return TenantInterface
     */
    public function getTenant()
    {
        return $this->manager->getTenant();
    }

    /**
     * @return TenantInterface[]
     */
    public function getTenants()
    {
        return $this->manager->getTenants();
    }
}
