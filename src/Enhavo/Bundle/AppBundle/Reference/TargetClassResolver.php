<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.03.18
 * Time: 15:19
 */

namespace Enhavo\Bundle\AppBundle\Reference;

use Doctrine\ORM\Proxy\Proxy;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class TargetClassResolver implements TargetClassResolverInterface
{
    use ContainerAwareTrait;

    public function resolveClass($object)
    {
        $className = get_class($object);
        if($object instanceof Proxy) {
            $className = get_parent_class($object);
        }

        $class = $this->getClassFromSylius($className);
        if($class !== null) {
            return $class;
        }

        return $className;
    }

    public function find($id, $class)
    {
        return $this->getRepository($class)->find($id);
    }

    /**
     * @param $class
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository($class)
    {
        $repository = $this->getRepositoryFromSylius($class);
        if($repository !== null) {
            return $repository;
        }

        return $this->container->get('doctrine.orm.entity_manager')->getRepository($class);
    }

    private function getRepositoryFromSylius($type)
    {
        $resources = $this->container->getParameter('sylius.resources');
        if(!isset($type, $resources)) {
            return null;
        }

        $type = explode('.', $type);
        $repositoryService = sprintf('%s.repository.%s', $type[0], $type[1]);
        return $this->container->get($repositoryService);
    }


    private function getClassFromSylius($className)
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