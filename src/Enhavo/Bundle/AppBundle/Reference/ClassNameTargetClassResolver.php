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

class ClassNameTargetClassResolver implements TargetClassResolverInterface
{
    use ContainerAwareTrait;

    public function resolveClass($object)
    {
        $className = get_class($object);
        if($object instanceof Proxy) {
            $className = get_parent_class($object);
        }

        return $className;
    }

    public function find($id, $class)
    {
        if(!class_exists($class)) {
            return null;
        }

        $repository = $this->getRepository($class);
        return $repository->find($id);
    }

    /**
     * @param $class
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function getRepository($class)
    {
        return $this->container->get('doctrine.orm.entity_manager')->getRepository($class);
    }
}