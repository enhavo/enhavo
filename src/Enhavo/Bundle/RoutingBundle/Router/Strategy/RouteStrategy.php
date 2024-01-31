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
use Symfony\Component\Routing\RouterInterface;

class RouteStrategy extends AbstractStrategy
{
    public function __construct(
        private RouterInterface $router,
    )
    {
    }

    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = []): string
    {
        if (empty($resource->getName())) {
            throw new UrlResolverException(sprintf('The route function getName of class "%s" returns null. Can\'t generate url', get_class($resource)));
        }

        return $this->router->generate($resource->getName(), $parameters, $referenceType);
    }

    public function getType()
    {
        return 'route';
    }
}
