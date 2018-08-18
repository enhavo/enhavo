<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.18
 * Time: 19:27
 */

namespace Enhavo\Bundle\RoutingBundle\Router\Strategy;


class IdStrategy
{
    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        if(!$resource instanceof Slugable) {
            throw new UrlResolverException(sprintf(
                'Strategy slug was configured, but class "%s" does not implement "%s"',
                get_class($resource),
                Slugable::class
            ));
        }

        if(!$resource instanceof ResourceInterface) {
            throw new UrlResolverException(sprintf(
                'Strategy slug was configured, but class "%s" does not implement "%s"',
                get_class($resource),
                ResourceInterface::class
            ));
        }

        if(empty($route)) {
            throw new UrlResolverException(sprintf(
                'Strategy slug was configured for class "%s" , but no route was set',
                get_class($resource)
            ));
        }

        try {
            return $this->router->generate($route, [
                'slug' => $resource->getSlug(),
                'id' => $resource->getId()
            ], $referenceType);
        } catch (InvalidParameterException $exception) {
            throw new UrlResolverException(sprintf(
                'Can\'t generate route: "%s"',
                $exception->getMessage()
            ));
        }
    }
}