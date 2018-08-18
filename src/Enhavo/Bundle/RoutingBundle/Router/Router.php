<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.08.18
 * Time: 19:27
 */

namespace Enhavo\Bundle\RoutingBundle\Router;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Router
{
    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $type = 'default') {

    }

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