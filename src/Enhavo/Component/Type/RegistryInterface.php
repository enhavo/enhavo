<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type;

interface RegistryInterface
{
    public function getType(string $name): TypeInterface;

    public function getExtensions(TypeInterface $type): array;
}
