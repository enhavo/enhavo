<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.03.18
 * Time: 15:19
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Reference;

use Doctrine\ORM\Proxy\Proxy;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SyliusTargetClassResolver implements TargetClassResolverInterface
{
    use ContainerAwareTrait;

    public function resolveClass($object)
    {
        $className = get_class($object);
        if($object instanceof Proxy) {
            $className = get_parent_class($object);
        }

        $resources = $this->container->getParameter('sylius.resources');
        foreach($resources as $type => $resource) {
            if($resource['classes']['model'] == $className) {
                return $type;
            }
        }
        return null;
    }

    public function find($id, $class)
    {
        $repository = $this->getRepository($class);
        if($repository === null) {
            return null;
        }
        return $repository->find($id);
    }

    /**
     * @param $class
     * @return ObjectRepository
     */
    private function getRepository($class)
    {
        $resources = $this->container->getParameter('sylius.resources');
        if(!array_key_exists($class, $resources)) {
            return null;
        }

        $type = explode('.', $class);
        $repositoryService = sprintf('%s.repository.%s', $type[0], $type[1]);
        /** @var ObjectRepository $repository */
        $repository = $this->container->get($repositoryService);
        return $repository;
    }
}
