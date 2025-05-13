<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Extension;

interface PropertyInterface
{
    public function addProperty(Property $property);

    public function getProperties(): array;

    public function hasProperty(string $name): bool;

    public function getProperty(string $name): ?Property;
}
