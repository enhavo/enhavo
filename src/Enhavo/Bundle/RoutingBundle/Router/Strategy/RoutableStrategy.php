<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.18
 * Time: 19:26
 */

namespace Enhavo\Bundle\RoutingBundle\Router\Strategy;

use Enhavo\Bundle\RoutingBundle\Exception\UrlResolverException;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Router\AbstractStrategy;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RoutableStrategy extends AbstractStrategy
{
    private Router $router;

    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = [])
    {
        /** @var RouteInterface $route */
        $route = $this->getProperty($resource, $options['property']);

        if ($route === null) {
            return null;
        }

        try {
            return $this->router->generate($route);
        } catch (RouteNotFoundException|UrlResolverException $e) {
            if ($options['error']) {
                throw new UrlResolverException($e->getMessage());
            }
            return null;
        }
    }

    public function getType()
    {
        return 'routable';
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'property' => 'route',
            'error' => true,
            'route_parameters' => [],
        ]);
    }
}
