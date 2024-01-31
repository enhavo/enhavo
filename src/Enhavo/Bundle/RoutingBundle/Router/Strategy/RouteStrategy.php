<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.18
 * Time: 19:26
 */

namespace Enhavo\Bundle\RoutingBundle\Router\Strategy;


use Enhavo\Bundle\RoutingBundle\Exception\UrlResolverException;
use Enhavo\Bundle\RoutingBundle\Router\AbstractStrategy;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RouteStrategy extends AbstractStrategy
{
    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = []): string
    {
        if ($resource?->getName() === null) {
            throw new UrlResolverException(sprintf('The route function getName of class "%s" returns null. Can\'t generate url', get_class($resource)));
        }

        return $this->getRouter()->generate($resource->getName(), $parameters, $referenceType);
    }

    public function getType()
    {
        return 'route';
    }
}
