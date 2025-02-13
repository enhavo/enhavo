<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 13:31
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Metadata;


class Reference
{
    const CASCADE_REMOVE = 'remove';
    const CASCADE_PERSIST = 'persist';

    public function __construct(
        private readonly string $property,
        private readonly string $nameField,
        private readonly string $idField,
        private readonly array $cascade = [],
    )
    {
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
