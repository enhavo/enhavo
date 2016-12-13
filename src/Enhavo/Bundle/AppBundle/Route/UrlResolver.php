<?php
/**
 * UrlResolver.php
 *
 * @since 06/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;

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

    public function resolve($resource , $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $className = get_class($resource);
        $config = $this->getConfig($className);

        $strategy = $config['strategy'];
        $route = $config['route'];

        $url = $this->getUrl($resource, $strategy, $route, $referenceType);

        if(empty($url)) {
            throw new \InvalidArgumentException(
                sprintf('Can\'t resolve route for class "%s". Maybe you need to add the class to url_resolver configuration', $className)
            );
        }

        return $url;
    }

    protected function getUrl($resource, $strategy, $route, $referenceType)
    {
        if($strategy == Routing::STRATEGY_ROUTE) {
            if(!$resource instanceof Routeable) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Class "%s" is not Routable',
                        get_class($resource)
                    )
                );
            }
            if(empty($resource->getRoute())) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Can\'t resolve route for class "%s", object is Routable but the route is null for id "%s"',
                        get_class($resource),
                        $resource->getId()
                    )
                );
            }
            return $this->router->generate($resource->getRoute(), [], $referenceType);
        }

        if($strategy == Routing::STRATEGY_SLUG && !empty($route) && $resource instanceof Slugable) {
            return $this->router->generate($route, [
                'slug' => $resource->getSlug()
            ], $referenceType);
        }

        if($strategy == Routing::STRATEGY_ID && !empty($route)) {
            return $this->router->generate($route, [
                'id' => $resource->getId()
            ], $referenceType);
        }

        if($strategy == Routing::STRATEGY_SLUG_ID && !empty($route) && $resource instanceof Slugable) {
            return $this->router->generate($route, [
                'slug' => $resource->getSlug(),
                'id' => $resource->getId()
            ], $referenceType);
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