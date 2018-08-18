<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.18
 * Time: 19:26
 */

namespace Enhavo\Bundle\RoutingBundle\Router\Strategy;


class RouteStrategy
{
    public function generate() {
        if(!$resource instanceof Routeable) {
            throw new UrlResolverException(
                sprintf(
                    'Class "%s" is not Routable',
                    get_class($resource)
                )
            );
        }
        if(empty($resource->getRoute())) {
            throw new UrlResolverException(
                sprintf(
                    'Can\'t resolve route for class "%s", object is Routable but the route is null for id "%s"',
                    get_class($resource),
                    $resource->getId()
                )
            );
        }
        try {
            return $this->router->generate($resource->getRoute(), [], $referenceType);
        } catch (InvalidParameterException $exception) {
            throw new UrlResolverException(sprintf(
                'Can\'t generate route: "%s"',
                $exception->getMessage()
            ));
        }
    }
}