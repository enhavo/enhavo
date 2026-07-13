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
use Enhavo\Bundle\RoutingBundle\Util\UniquePrefixGenerator;

class TenantPrefixGenerator extends PrefixGenerator
{
    public function __construct(
        UniquePrefixGenerator $uniquePrefixGenerator,
        private ResolverInterface $resolver,
    ) {
        parent::__construct($uniquePrefixGenerator);
    }

    protected function createUniquePrefix(array $properties, $resource, array $options): string
    {
        return $this->uniquePrefixGenerator->generate($properties, $resource, [
            'format' => $options['format'],
            'max_length' => $options['max_length'],
            'exists' => fn () => ['tenant' => $this->resolver->getTenant()],
        ]);
    }

    public function getType()
    {
        return 'tenancy_aware_prefix';
    }
}