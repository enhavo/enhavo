<?php
/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class DoctrineOrmYaml
{
    /** @var string */
    private $namespace;

    /** @var string */
    private $name;

    /** @var string */
    private $tableName;

    /** @var array */
    private $fields;

    /**
     * @param string $namespace
     * @param string $name
     * @param string $tableName
     * @param array $fields
     */
    public function __construct(string $namespace, string $name, string $tableName, array $fields)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->tableName = $tableName;
        $this->fields = $fields;
    }

    public function getFields(): array
    {
        $properties = [];
        foreach ($this->fields as $key => $config) {
            $properties[] = $this->getField($key);
        }

        return $properties;
    }

    public function getField($key): DoctrineOrmField
    {
        return new DoctrineOrmField($key, $this->fields[$key]);
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
