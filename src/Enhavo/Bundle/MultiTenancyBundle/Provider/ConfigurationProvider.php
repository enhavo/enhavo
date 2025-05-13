<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Provider;

use Enhavo\Bundle\MultiTenancyBundle\Model\Tenant;
use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ConfigurationProvider implements ProviderInterface
{
    /** @var array */
    private $config;

    /** @var string */
    private $class;

    /**
     * ConfigurationProvider constructor.
     *
     * @param string $class
     */
    public function __construct(array $config, $class = Tenant::class)
    {
        $this->config = $config;
        $this->class = $class;
    }

    /**
     * @return TenantInterface[]
     */
    public function getTenants(): array
    {
        $tenants = [];
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->config as $key => $tenantConfig) {
            $tenant = new $this->class();
            foreach ($tenantConfig as $property => $value) {
                $propertyAccessor->setValue($tenant, $property, $value);
            }
            $propertyAccessor->setValue($tenant, 'key', $key);
            $tenants[] = $tenant;
        }

        return $tenants;
    }
}
