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

class AssignResolver implements ResolverInterface
{
    /** @var ProviderInterface */
    private $provider;

    /**
     * @var string|null
     */
    private $assignedKey;

    /**
     * AssignResolver constructor.
     *
     * @param string|null $initialAssigned
     */
    public function __construct(ProviderInterface $provider, $initialAssigned = null)
    {
        $this->provider = $provider;
        $this->assignedKey = $initialAssigned;
    }

    public function assign(?string $key)
    {
        $this->assignedKey = $key;
    }

    public function getTenant(): ?TenantInterface
    {
        if (null === $this->assignedKey) {
            return null;
        }
        foreach ($this->provider->getTenants() as $tenant) {
            if ($tenant->getKey() === $this->assignedKey) {
                return $tenant;
            }
        }

        return null;
    }
}
