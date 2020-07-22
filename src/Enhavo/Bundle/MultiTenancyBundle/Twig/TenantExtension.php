<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Twig;

use Enhavo\Bundle\MultiTenancyBundle\Manager\TenantManager;
use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TenantExtension extends AbstractExtension
{
    /** @var TenantManager */
    private $manager;

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
