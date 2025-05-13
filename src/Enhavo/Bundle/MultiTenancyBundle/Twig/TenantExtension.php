<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     */
    public function __construct(TenantManager $manager)
    {
        $this->manager = $manager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('tenant', [$this, 'getTenant']),
            new TwigFunction('tenants', [$this, 'getTenants']),
        ];
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
