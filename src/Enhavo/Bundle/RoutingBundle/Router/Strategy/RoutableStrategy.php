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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RoutableStrategy extends AbstractStrategy
{
    /**
     * @param $resource
     * @param array $parameters
     * @param int $referenceType
     * @param array $options
     * @return string
     * @throws UrlResolverException
     */
    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = [])
    {
        /** @var RouteInterface $route */
        $route = $this->getProperty($resource, $options['property']);

        if($route->getName() == null) {
            throw new UrlResolverException(sprintf('The route function getName of class "%s" returns null. Can\'t generate url', get_class($resource)));
        }

        try {
            $parameters = array_merge($options['route_parameters'], $parameters);
            return $this->getRouter()->generate($route->getName(), $parameters, $referenceType);
        } catch (RouteNotFoundException $e) {
            if($options['error']) {
                throw new UrlResolverException($e->getMessage());
            }
            return '#';
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
