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

use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TenantConditionGenerator extends AbstractGenerator
{
    public function generate($resource, $options = [])
    {
        $resolveValue = $this->getProperty($resource, $options['resolve_property']);
        /** @var Route $route */
        $route = $this->getProperty($resource, $options['route_property']);
        if ($route && $resolveValue) {
            /** @var RouteInterface $route */
            if (!$options['overwrite'] && $route->getCondition()) {
                return;
            }
            $route->setCondition(sprintf($options['condition'], $resolveValue));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'resolve_property' => null,
            'route_property' => 'route',
            'condition' => 'context.getTenant() == "%s"',
            'overwrite' => true,
        ]);
    }

    public function getType()
    {
        return 'tenant_condition';
    }
}
