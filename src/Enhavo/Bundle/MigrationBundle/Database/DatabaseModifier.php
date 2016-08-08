<?php

namespace Enhavo\Bundle\MigrationBundle\Database;

use Doctrine\ORM\EntityManager;

class DatabaseModifier
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $tableSchema;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->tableSchema = $entityManager->getConnection()->getDatabase();
    }

    /**
     * True if the database contains table $table, false otherwise.
     *
     * @param string $table The name of the database table
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function tableExists($table)
    {
        $statement = $this->entityManager->getConnection()->prepare('SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = :table AND TABLE_SCHEMA = :schema');
        $statement->bindValue('table', $table);
        $statement->bindValue('schema', $this->tableSchema);
        $statement->execute();
        return count($statement->fetchAll()) > 0;
    }

    /**
     * Generates an identifier as used for indices, contraints etc by doctrine.
     * The result is identical with identifiers produced by doctrine-dbal (v2.5.4).
     *
     * @see Doctrine\DBAL\Schema\AbstractAsset::_generateIdentifierName()
     *
     * @param string $tableName Name of the table
     * @param string $columnName Name of the column
     * @param string $prefix Prefix, e.g. "fk" for foreign key
     * @return string Identifier
     */
    public function generateDoctrineSchemaIdentifierName($tableName, $columnName, $prefix)
    {
        $columnNames = array($tableName, $columnName);
        $hash = implode("", array_map(function ($column) {
            return dechex(crc32($column));
        }, $columnNames));

        return substr(strtoupper($prefix . "_" . $hash), 0, $this->entityManager->getConnection()->getDatabasePlatform()->getMaxIdentifierLength());
    }

    /**
     * Executes an, optionally parametrized, SQL query.
     *
     * If the query is parametrized, a prepared statement is used.
     * If an SQLLogger is configured, the execution is logged.
     *
     * @see Doctrine\DBAL\Connection::executeQuery()
     *
     * @param string    $query  The SQL query to execute.
     * @param array     $params The parameters to bind to the query, if any.
     * @param array     $types  The types the previous parameters are in.
     *
     * @return \Doctrine\DBAL\Driver\Statement The executed statement.
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function executeQuery($query, array $params = array(), $types = array())
    {
        return $this->entityManager->getConnection()->executeQuery($query, $params, $types, null);
    }

    public function renameTable($source, $target)
    {
        return $this->executeQuery(sprintf('ALTER TABLE `%s` RENAME `%s`', $source, $target));
    }

    /**
     * @param string $tableName
     * @param array $columnNames
     */
    public function addColumnsToTableIfExists($tableName, $columnNames, $type = 'INT')
    {
        if (!$this->tableExists($tableName)) {
            return;
        }

        $sql = sprintf('ALTER TABLE %s ADD %s %s DEFAULT NULL', $tableName, $columnNames[0], $type);
        if (count($columnNames) > 1) {
            foreach($columnNames as $key => $columnName) {
                if ($key > 0) {
                    $sql .= sprintf(', ADD %s INT DEFAULT NULL', $columnName);
                }
            }
        }
        $this->executeQuery($sql);

        foreach($columnNames as $columnName) {
            $this->executeQuery(
                sprintf('ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES media_file (id)',
                    $tableName,
                    $this->generateDoctrineSchemaIdentifierName($tableName, $columnName, 'fk'),
                    $columnName
                )
            );
            $this->executeQuery(
                sprintf('CREATE INDEX %s ON %s (%s)',
                    $this->generateDoctrineSchemaIdentifierName($tableName, $columnName, 'idx'),
                    $tableName,
                    $columnName
                )
            );
        }
    }

    /**
     * @param string $targetTable
     * @param string $targetTableIdColumn
     * @param string $targetTableTargetColumn
     * @param string $sourceTable
     * @param string $sourceTableJoinColumn
     * @param string $sourceTableSourceColumn
     */
    public function copyConnectionsIfTableExists($targetTable, $targetTableIdColumn, $targetTableTargetColumn, $sourceTable, $sourceTableJoinColumn, $sourceTableSourceColumn)
    {
        if (!($this->tableExists($targetTable) && $this->tableExists($sourceTable))) {
            return;
        }

        $statement = $this->executeQuery(
            sprintf('SELECT t0.%s, t0.%s FROM %s t0 INNER JOIN %s t1 ON t1.%s = t0.%s',
                $sourceTableJoinColumn,
                $sourceTableSourceColumn,
                $sourceTable,
                $targetTable,
                $targetTableIdColumn,
                $sourceTableJoinColumn
            )
        );
        $values = $statement->fetchAll();

        foreach($values as $value) {
            $this->executeQuery(
                sprintf('UPDATE %s SET %s = :value WHERE %s = :id',
                    $targetTable,
                    $targetTableTargetColumn,
                    $targetTableIdColumn
                ),
                array(
                    'value' => $value[$sourceTableSourceColumn],
                    'id'    => $value[$sourceTableJoinColumn]
                )
            );
        }
    }

    public function dropTableIfExists($joinTable)
    {
        if (!$this->tableExists($joinTable)) {
            return;
        }

        $this->executeQuery(
            sprintf('DROP TABLE %s',
                $joinTable
            )
        );
    }

    public function renameColumn($table, $from, $to, $type = 'INT')
    {
        $this->executeQuery(
            sprintf('ALTER TABLE `%s` CHANGE `%s` `%s` %s;',
                $table,
                $from,
                $to,
                $type
            )
        );
    }
}
