<?php
/**
 * UrlResolver.php
 *
 * @since 06/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;

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

    public function resolve($resource)
    {
        $className = get_class($resource);
        $config = $this->getConfig($className);

        $strategy = $config['strategy'];
        $route = $config['route'];

        $url = $this->getUrl($resource, $strategy, $route);

        if(empty($url)) {
            throw new \InvalidArgumentException(
                sprintf('Can\'t resolve route for class "%s". Maybe you need to add the class to enhavo_app.route.url_resolver configuration', $className)
            );
        }

        return $url;
    }

    protected function getUrl($resource, $strategy, $route)
    {
        if($strategy == Routing::STRATEGY_ROUTE && $resource instanceof Routeable) {
            if(empty($resource->getRoute())) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Can\'t resolve route for class "%s", object is Routable but the route is null for id "%s"',
                        get_class($resource),
                        $resource->getId()
                    )
                );
            }
            return $this->router->generate($resource->getRoute());
        }

        if($strategy == Routing::STRATEGY_SLUG && !empty($route) && $resource instanceof Slugable) {
            return $this->router->generate($route, [
                'slug' => $resource->getSlug()
            ]);
        }

        if($strategy == Routing::STRATEGY_ID && !empty($route)) {
            return $this->router->generate($route, [
                'id' => $resource->getId()
            ]);
        }

        if($strategy == Routing::STRATEGY_SLUG_ID && !empty($route) && $resource instanceof Slugable) {
            return $this->router->generate($route, [
                'slug' => $resource->getSlug(),
                'id' => $resource->getId()
            ]);
        }

        return null;
    }

    protected function getFromResources($className)
    {
        foreach($this->resources as $type => $resource) {
            if($resource['classes']['model'] == $className) {
                if(isset($resource['routing'])) {
                    return $resource['routing'];
                }
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
        return $this->getFromResources($className);
    }
}