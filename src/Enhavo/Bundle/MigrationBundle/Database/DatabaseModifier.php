<?php

namespace Enhavo\Bundle\MigrationBundle\Service;

use Doctrine\ORM\EntityManager;

class DatabaseModifier
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
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
        $statement = $this->entityManager->getConnection()->prepare('SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = :table');
        $statement->bindValue('table', $table);
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
}
