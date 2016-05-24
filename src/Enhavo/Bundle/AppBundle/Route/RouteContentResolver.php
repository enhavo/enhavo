<?php
/**
 * RouteContentResolver.php
 *
 * @since 18/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;

use Symfony\Component\DependencyInjection\Container;

class RouteContentResolver
{
    /**
     * @var RouteContentCollector
     */
    protected $collector;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param RouteContentCollector $collector
     * @param Container $container
     */
    public function __construct(RouteContentCollector $collector, Container $container)
    {
        $this->collector = $collector;
        $this->container = $container;
    }

    /**
     * @param $type
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository($type)
    {
        $repository = $this->getRepositoryFromSylius($type);
        if($repository !== null) {
            return $repository;
        }

        foreach ($this->collector->getCollection() as $item) {
            /** @var $item \Enhavo\Bundle\AppBundle\Route\RouteContentLoader */
            if($item->getType() === $type) {
                return $this->container->get($item->getRepository());
            }
        }
        return null;
    }

    public function getType($content)
    {
        $className = get_class($content);

        $type = $this->getTypeFromSylius($className);
        if($type !== null) {
            return $type;
        }

        foreach ($this->collector->getCollection() as $item) {
            /** @var $item \Enhavo\Bundle\AppBundle\Route\RouteContentLoader */
            if($item->getClassName() === $className) {
                return $item->getType();
            }
        }
        return null;
    }

    protected function getRepositoryFromSylius($type)
    {
        $resources = $this->container->getParameter('sylius.resources');
        if(!isset($type, $resources)) {
            return null;
        }

        $type = explode('.', $type);
        $repositoryService = sprintf('%s.repository.%s', $type[0], $type[1]);
        return $this->container->get($repositoryService);
    }


    protected function getTypeFromSylius($className)
    {
        $resources = $this->container->getParameter('sylius.resources');
        foreach($resources as $type => $resource) {
            if($resource['classes']['model'] == $className) {
                return $type;
            }
        }
        return null;
    }
}