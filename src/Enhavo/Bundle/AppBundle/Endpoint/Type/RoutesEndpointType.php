<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Routing\RouteCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoutesEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly RouteCollectorInterface $routeCollector,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $routesCollection = $this->routeCollector->getRouteCollection($options['groups']);
        $data->set('routes', $this->normalize($routesCollection, null, ['groups' => 'endpoint']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'groups' => null,
        ]);
    }

    public static function getName(): ?string
    {
        return 'routes';
    }
}
