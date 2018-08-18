<?php
/**
 * UrlResolver.php
 *
 * @since 06/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Routing;

use Doctrine\Common\Proxy\Proxy;
use Enhavo\Bundle\AppBundle\Exception\UrlResolverException;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class UrlResolver implements UrlResolverInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $resources;

    /**
     * @var array
     */
    protected $config;

    public function __construct(RouterInterface $router, $resources, $config)
    {
        $this->router = $router;
        $this->resources = $resources;
        $this->config = $config;
    }

    /**
     * @param $resource
     * @param int $referenceType
     * @return null|string
     * @throws UrlResolverException
     */
    public function resolve($resource , $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $className = get_class($resource);
        if($resource instanceof Proxy) {
            $className = get_parent_class($resource);
        }

        $config = $this->getConfig($className);

        $strategy = $config['strategy'];
        $route = $config['route'];

        $url = $this->getUrl($resource, $strategy, $route, $referenceType);

        if(empty($url)) {
            throw new UrlResolverException(
                sprintf('Can\'t resolve route for class "%s". Maybe you need to add the class to url_resolver configuration', $className)
            );
        }

        return $url;
    }

    /**
     * @param $resource
     * @param $strategy
     * @param $route
     * @param $referenceType
     * @return null|string
     * @throws UrlResolverException
     */
    protected function getUrl($resource, $strategy, $route, $referenceType)
    {
        if($strategy == Routing::STRATEGY_ROUTE)
        {
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

        if($strategy == Routing::STRATEGY_SLUG)
        {
            if(!$resource instanceof Slugable) {
                throw new UrlResolverException(sprintf(
                    'Strategy slug was configured, but class "%s" does not implement "%s"',
                    get_class($resource),
                    Slugable::class
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
                    'slug' => $resource->getSlug()
                ], $referenceType);
            } catch (InvalidParameterException $exception) {
                throw new UrlResolverException(sprintf(
                    'Can\'t generate route: "%s"',
                    $exception->getMessage()
                ));
            }
        }

        if($strategy == Routing::STRATEGY_ID)
        {
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
                    'id' => $resource->getId()
                ], $referenceType);
            } catch (InvalidParameterException $exception) {
                throw new UrlResolverException(sprintf(
                    'Can\'t generate route: "%s"',
                    $exception->getMessage()
                ));
            }
        }

        if($strategy == Routing::STRATEGY_SLUG_ID)
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

        return null;
    }
    
    protected function getTypeFromSylius($className)
    {
        foreach($this->resources as $type => $resource) {
            if($resource['classes']['model'] == $className) {
                return $type;
            }
        }
        return null;
    }

    protected function getConfig($className)
    {
        $type = $this->getTypeFromSylius($className);
        foreach($this->config as $resource) {
            if($resource['model'] == $className) {
                return $resource;
            }
            if($resource['model'] == $type) {
                return $resource;
            }
        }
    }
}