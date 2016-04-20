<?php

namespace Enhavo\Bundle\MigrationBundle\Service;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Enhavo\Bundle\MigrationBundle\Entity\DatabaseVersion;

class DatabaseModifier
{
    const DATABASE_VERSION_TABLE = 'migration_database_version';

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
     * Gets the enhavo database version from the database. If the database has no version, returns null.
     *
     * @return null|string The enhavo database version
     */
    public function getDatabaseVersion()
    {
        if (!$this->tableExists(self::DATABASE_VERSION_TABLE)) {
            return null;
        }
        $versionItem = $this->entityManager->getRepository('EnhavoMigrationBundle:DatabaseVersion')->find(1);
        if ($versionItem) {
            return $versionItem->getVersion();
        }
        return null;
    }

    /**
     * Updates the enhavo database version. If the database previously had no enhavo database version, it is created.
     *
     * @param string $version The new version number
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function setDatabaseVersion($version)
    {
        if (!$this->tableExists(self::DATABASE_VERSION_TABLE)) {
            $this->generateDatabaseVersionTable();
        }
        $versionItem = $this->entityManager->getRepository('EnhavoMigrationBundle:DatabaseVersion')->find(1);
        if (!$versionItem) {
            $versionItem = new DatabaseVersion();
            $versionItem->setVersion($version);
        } else {
            $versionItem->setVersion($version);
        }
        $this->entityManager->persist($versionItem);
        $this->entityManager->flush();
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

    /**
     * Generates table for entity DatabaseVersion. Does not check if this table already exists.
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    private function generateDatabaseVersionTable()
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($this->entityManager);
        $schema = $tool->getSchemaFromMetadata($metadata);

        $versionTable = null;
        foreach ($schema->getTables() as $table) {
            if ($table->getShortestName($schema->getName()) == self::DATABASE_VERSION_TABLE) {
                $versionTable = $table;
                break;
            }
        }
        if ($versionTable) {
            $platform = $this->entityManager->getConnection()->getDatabasePlatform();
            $sql = $platform->getCreateTableSQL($versionTable, AbstractPlatform::CREATE_INDEXES);

            $this->entityManager->getConnection()->executeQuery($sql[0]);
        } else {
            throw new \Exception('Error: Couldn\'t find database version entity definition.');
        }
    }
}
