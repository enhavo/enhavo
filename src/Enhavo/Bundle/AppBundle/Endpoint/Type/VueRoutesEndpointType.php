<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Vue\RouteProvider\VueRouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VueRoutesEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly VueRouteProviderInterface $provider,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        if ($request->query->has('path')) {
            $path = $request->get('path');
            $route = $this->provider->getRoute($path, $options['groups']);
            if (null === $route) {
                $data->set('vue_routes', []);
            } else {
                $data->set('vue_routes', $this->normalize([$route]));
            }
        } else {
            $routes = $this->provider->getRoutes($options['groups']);
            $data->set('vue_routes', $this->normalize($routes));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'vue_routes';
    }
}
