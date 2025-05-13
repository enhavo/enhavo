<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class DoctrineOrmYaml
{
    public function __construct(
        private string $tableName,
        private array $fields,
    ) {
    }

    public function getFields(): array
    {
        $properties = [];
        foreach ($this->fields as $key => $config) {
            if (!isset($config['relation'])) {
                $properties[] = $this->getField($key);
            }
        }

        return $properties;
    }

    public function getRelations(string $type): array
    {
        $properties = [];
        foreach ($this->fields as $key => $config) {
            $hit = isset($config['relation']) && $config['relation']['type'] === $type;
            if ($hit) {
                $properties[] = $this->getRelation($key);
            }
        }

        return $properties;
    }

    public function getAttributeType($key): string
    {
        $relation = $this->getRelation($key);

        if ($relation) {
            return $relation->getType();
        }

        return 'Column';
    }

    public function getAttributeOptions($key): array
    {
        $field = $this->getField($key);
        $relation = $this->getRelation($key);

        if ($relation) {
            $result = [];
        } else {
            $typeOption = [];
            if ($field->getOrmType()) {
                $typeOption = [
                    'type' => sprintf('Types::%s', strtoupper($field->getOrmType())),
                ];
            }
            $result = $typeOption + [
                'nullable' => $field->getNullableString(),
            ];
        }

        return $result;
    }

    public function getField($key): DoctrineOrmField
    {
        return new DoctrineOrmField($key, $this->fields[$key]);
    }

    public function getRelation($key): ?DoctrineOrmRelation
    {
        return isset($this->fields[$key]['relation']) ? new DoctrineOrmRelation($key, $this->fields[$key]['relation']) : null;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }
}
