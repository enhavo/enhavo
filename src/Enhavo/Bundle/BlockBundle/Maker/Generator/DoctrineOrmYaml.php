<?php
/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class DoctrineOrmYaml
{
    /** @var string */
    private $tableName;

    /** @var array */
    private $fields;

    /**
     * @param string $tableName
     * @param array $fields
     */
    public function __construct(string $tableName, array $fields)
    {
        $this->tableName = $tableName;
        $this->fields = $fields;
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

    public function getField($key): DoctrineOrmField
    {
        return new DoctrineOrmField($key, $this->fields[$key]);
    }

    public function getRelation($key): DoctrineOrmRelation
    {
        return new DoctrineOrmRelation($key, $this->fields[$key]['relation']);
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

}
