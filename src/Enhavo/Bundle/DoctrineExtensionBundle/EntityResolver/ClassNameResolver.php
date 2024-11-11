<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.03.18
 * Time: 15:19
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Proxy\Proxy;

class ClassNameResolver implements EntityResolverInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * ClassNameResolver constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getName($entity): string
    {
        if (is_string($entity)) {
            return $entity;
        }
        $className = get_class($entity);
        if($entity instanceof Proxy) {
            $className = get_parent_class($entity);
        }

        return $className;
    }

    public function getEntity(int $id, string $name): ?object
    {
        if(!class_exists($name)) {
            return null;
        }

        return $this->em->getRepository($name)->find($id);
    }
}
