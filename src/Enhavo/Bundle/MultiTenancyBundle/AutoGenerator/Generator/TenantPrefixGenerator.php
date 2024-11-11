<?php

namespace Enhavo\Bundle\MultiTenancyBundle\AutoGenerator\Generator;

use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Enhavo\Bundle\ResourceBundle\Repository\FilterRepositoryInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator\PrefixGenerator;

class TenantPrefixGenerator extends PrefixGenerator
{
    /**
     * @var FilterRepositoryInterface
     */
    private $routeRepository;

    /**
     * @var ResolverInterface
     */
    private $resolver;

    public function __construct($routeRepository, ResolverInterface $resolver)
    {
        parent::__construct($routeRepository);
        $this->routeRepository = $routeRepository;
        $this->resolver = $resolver;
    }

    protected function existsPrefix($prefix, $resource, array $options): bool
    {
        $tenant = $this->resolver->getTenant();
        $results = $this->routeRepository->findBy([
            'staticPrefix' => $prefix,
            'tenant' => $tenant
        ]);

        return count($results);
    }

    public function getType()
    {
        return 'tenancy_aware_prefix';
    }
}
