<?php


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
     * @param array $config
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
            $tenant = new $this->class;
            foreach($tenantConfig as $property => $value) {
                $propertyAccessor->setValue($tenant, $property, $value);
            }
            $propertyAccessor->setValue($tenant, 'key', $key);
            $tenants[] = $tenant;
        }

        return $tenants;
    }
}
