<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.03.18
 * Time: 15:19
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver;

interface EntityResolverInterface
{
    /**
     * @param $entity
     * @return string
     */
    public function getName($entity): string;

    /**
     * @param int $id
     * @param string $name
     * @return object|null
     */
    public function getEntity(int $id, string $name): ?object;
}
