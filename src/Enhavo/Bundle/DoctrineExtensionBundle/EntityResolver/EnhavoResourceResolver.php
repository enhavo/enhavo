<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\Proxy;
use Enhavo\Bundle\DoctrineExtensionBundle\Exception\ResolveException;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;

class EnhavoResourceResolver implements EntityResolverInterface
{
    public function __construct(
        private readonly array $resources,
        private readonly ResourceManager $resourceManager,
    ) {
    }

    public function getName($entity): string
    {
        if (is_string($entity)) {
            $className = $entity;
        } else {
            $className = get_class($entity);
            if ($entity instanceof Proxy) {
                $className = get_parent_class($entity);
            }
        }

        foreach ($this->resources as $type => $resource) {
            if ($resource['classes']['model'] == $className) {
                return $type;
            }
        }

        throw ResolveException::invalidEntity($entity);
    }

    public function getEntity(int $id, string $name): ?object
    {
        $repository = $this->getRepository($name);
        if (null === $repository) {
            return null;
        }

        return $repository->find($id);
    }

    private function getRepository($class): ?EntityRepository
    {
        if (!array_key_exists($class, $this->resources)) {
            return null;
        }

        $repository = $this->resourceManager->getRepository($class);

        return $repository;
    }
}
