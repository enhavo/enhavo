<?php

/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.03.18
 * Time: 15:19
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver;

use Doctrine\ORM\Proxy\Proxy;
use Doctrine\Common\Persistence\ObjectRepository;
use Enhavo\Bundle\DoctrineExtensionBundle\Exception\ResolveException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SyliusResourceResolver implements EntityResolverInterface
{
    use ContainerAwareTrait;

    /** @var array */
    private $resources;

    /**
     * SyliusResourceResolver constructor.
     * @param array $resources
     */
    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    public function getName(object $object): string
    {
        $className = get_class($object);
        if($object instanceof Proxy) {
            $className = get_parent_class($object);
        }

        foreach($this->resources as $type => $resource) {
            if($resource['classes']['model'] == $className) {
                return $type;
            }
        }

        throw ResolveException::invalidEntity($object);
    }

    public function getEntity(int $id, string $class): ?object
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
        if(!array_key_exists($class, $this->resources)) {
            return null;
        }

        $type = explode('.', $class);
        $repositoryService = sprintf('%s.repository.%s', $type[0], $type[1]);
        /** @var ObjectRepository $repository */
        $repository = $this->container->get($repositoryService);
        return $repository;
    }
}
