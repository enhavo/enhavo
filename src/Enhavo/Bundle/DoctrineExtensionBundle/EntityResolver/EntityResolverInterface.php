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

interface EntityResolverInterface
{
    public function getName($entity): string;

    public function getEntity(int $id, string $name): ?object;
}
