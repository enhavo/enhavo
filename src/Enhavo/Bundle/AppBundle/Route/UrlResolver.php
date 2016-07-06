<?php
/**
 * UrlResolver.php
 *
 * @since 06/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;

use Symfony\Component\Routing\RouterInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class UrlResolver implements UrlResolverInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $syliusResources;

    public function __construct(RouterInterface $router, $syliusResources)
    {
        $this->router = $router;
        $this->syliusResources = $syliusResources;
    }

    public function resolve($resource)
    {
        $routeConfig = $this->getRoutingConfig(get_class($resource));

        if($routeConfig == null) {
            return null;
        }

        if($routeConfig['strategy'] === Routing::STRATEGY_ROUTE && $resource instanceof Routeable) {
            return $this->router->generate($resource->getRoute());
        }

        if($routeConfig['strategy'] === Routing::STRATEGY_SLUG && isset($routeConfig['route']) && $resource instanceof Slugable) {
            return $this->router->generate($routeConfig['route'], [
                'slug' => $resource->getSlug()
            ]);
        }

        if($routeConfig['strategy'] === Routing::STRATEGY_ID && isset($routeConfig['route']) && $resource instanceof ResourceInterface) {
            return $this->router->generate($routeConfig['route'], [
                'id' => $resource->getId()
            ]);
        }

        if($routeConfig['strategy'] === Routing::STRATEGY_SLUG_ID && isset($routeConfig['route']) && $resource instanceof ResourceInterface && $resource instanceof Slugable) {
            return $this->router->generate($routeConfig['route'], [
                'slug' => $resource->getId(),
                'id' => $resource->getSlug()
            ]);
        }

        return null;
    }

    protected function getRoutingConfig($className)
    {
        foreach($this->syliusResources as $type => $resource) {
            if($resource['classes']['model'] == $className) {
                if(isset($resource['routing'])) {
                    return $resource['routing'];
                }
            }
        }
        return null;
    }
}