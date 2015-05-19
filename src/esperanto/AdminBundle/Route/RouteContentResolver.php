<?php
/**
 * RouteContentResolver.php
 *
 * @since 18/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Route;

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
        foreach ($this->collector->getCollection() as $item) {
            /** @var $item \esperanto\AdminBundle\Route\RouteContentLoader */
            if($item->getType() === $type) {
                return $this->container->get($item->getRepository());
            }
        }
        return null;
    }

    public function getType($content)
    {
        $className = get_class($content);
        foreach ($this->collector->getCollection() as $item) {
            /** @var $item \esperanto\AdminBundle\Route\RouteContentLoader */
            if($item->getClassName() === $className) {
                return $item->getType();
            }
        }
        return null;
    }
}