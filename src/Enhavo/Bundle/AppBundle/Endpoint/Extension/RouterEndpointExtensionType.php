<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Enhavo\Bundle\AppBundle\Routing\RouteCollectorInterface;
use Enhavo\Bundle\AppBundle\Twig\TwigRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouterEndpointExtensionType extends AbstractEndpointTypeExtension
{
    public function __construct(
        private readonly TwigRouter $twigRouter,
        private readonly RouteCollectorInterface $routeCollector,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if ($options['routes']) {
            $routesCollection = $this->routeCollector->getRouteCollection($options['routes']);
            $context->set('routes', $routesCollection);
            $data->set('routes', $this->normalize($routesCollection, null, ['groups' => 'endpoint']));
        }

        if (!$request->get('_format') || $request->get('_format') === 'html') {
            $this->twigRouter->addRouteCollection($context->get('routes'));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'routes' => null,
        ]);
    }

    public static function getExtendedTypes(): array
    {
        return [ViewEndpointType::class];
    }
}
