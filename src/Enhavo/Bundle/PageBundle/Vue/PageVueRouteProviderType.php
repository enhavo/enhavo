<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\PageBundle\Vue;

use Enhavo\Bundle\AppBundle\Vue\RouteProvider\AbstractVueRouteProviderType;
use Enhavo\Bundle\AppBundle\Vue\RouteProvider\VueRoute;
use Enhavo\Bundle\AppBundle\Vue\RouteProvider\VueRouteProviderTypeInterface;
use Enhavo\Bundle\RoutingBundle\Vue\RoutingVueRouteProviderType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageVueRouteProviderType extends AbstractVueRouteProviderType implements VueRouteProviderTypeInterface
{
    public static function getName(): ?string
    {
        return 'page';
    }

    public static function getParentType(): ?string
    {
        return RoutingVueRouteProviderType::class;
    }

    public function getRoutes($options, array|string|null $groups = null): array
    {
        return $this->parent->getRoutes($options, $groups);
    }

    public function getRoute($options, $path, array|string|null $groups = null): ?VueRoute
    {
        return $this->parent->getRoute($options, $groups);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'resource_key' => 'enhavo_page.page',
            'meta_name' => 'page',
        ]);
    }
}
