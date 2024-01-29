<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Enhavo\Bundle\AppBundle\Twig\TwigRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouterEndpointExtensionType extends AbstractEndpointTypeExtension
{
    public function __construct(
        private readonly TwigRouter $twigRouter,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if (!$request->get('_format') || $request->get('_format') === 'html') {
            if ($context->has('routes')) {
                $this->twigRouter->addRouteCollection($context->get('routes'));
            } else if ($data->has('routes')) {
                $routes = $data->get('routes');
                $routeCollection = new RouteCollection();
                foreach ($routes as $name => $routeParameter) {
                    $route = new Route($routeParameter['path']);
                    $routeCollection->add($name, $route);
                }
                $this->twigRouter->addRouteCollection($routeCollection);
            }
        }
    }

    public static function getExtendedTypes(): array
    {
        return [ViewEndpointType::class];
    }
}
