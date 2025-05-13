<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Metadata;

class Reference
{
    public const CASCADE_REMOVE = 'remove';
    public const CASCADE_PERSIST = 'persist';

    public function __construct(
        private readonly string $property,
        private readonly string $nameField,
        private readonly string $idField,
        private readonly array $cascade = [],
    ) {
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getNameField(): string
    {
        return $this->nameField;
    }

    public function getIdField(): string
    {
        return $this->idField;
    }

    public function getCascade(): array
    {
        return $this->cascade;
    }

    public function hasCascade(string $type): bool
    {
        return in_array($type, $this->cascade);
    }
}
