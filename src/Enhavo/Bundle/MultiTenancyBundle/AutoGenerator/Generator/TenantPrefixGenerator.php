<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\AutoGenerator\Generator;

use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator\PrefixGenerator;
use Enhavo\Bundle\RoutingBundle\Repository\RouteRepository;
use Enhavo\Bundle\RoutingBundle\Util\UniquePrefixGenerator;

class TenantPrefixGenerator extends PrefixGenerator
{
    public function __construct(
        UniquePrefixGenerator $uniquePrefixGenerator,
        private ResolverInterface $resolver,
    ) {
        parent::__construct($uniquePrefixGenerator);
    }

    protected function createPrefix(array $properties, $resource, array $options): string
    {
        return $this->uniquePrefixGenerator->generate($properties, [
            'format' => $options['format'],
            'max_length' => $options['max_length'],
            'exists' => function (RouteRepository $repository, string $prefix) {
                return count($repository->findBy(['tenant' => $this->resolver->getTenant(), 'staticPrefix' => $prefix])) > 0;
            },
        ]);
    }

    public function getType()
    {
        return 'tenancy_aware_prefix';
    }
}
