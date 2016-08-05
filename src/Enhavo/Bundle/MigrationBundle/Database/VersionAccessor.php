<?php
/**
 * VersionAccessor.php
 *
 * @since 27/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MigrationBundle\Database;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\MigrationBundle\Entity\Version;

class VersionAccessor
{
    const VERSION_TABLE = 'migration_version';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var DatabaseModifier
     */
    protected $modifier;

    public function __construct(EntityManager $entityManager, DatabaseModifier $modifier)
    {
        $this->entityManager = $entityManager;
        $this->modifier = $modifier;
    }

    /**
     * Gets the enhavo database version from the database. If the database has no version, returns null.
     *
     * @return null|string The enhavo database version
     */
    public function getVersion()
    {
        if ($this->modifier->tableExists('migration_database_version')) {
            return '0.1.0';
        }

        if ($this->modifier->tableExists('migration_version')) {
            return null;
        }

        $version = $this->entityManager->getRepository('EnhavoMigrationBundle:Version')->getVersion();
        return $version;
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
    public function setVersion($version)
    {
        if (!$this->modifier->tableExists(self::VERSION_TABLE)) {
            $this->generateDatabaseVersionTable();
        }

        /** @var Version $version */
        $versions = $this->entityManager->getRepository('EnhavoMigrationBundle:Version')->findAll();
        if(empty($versions)) {
            $entityVersion = new Version();
            $this->entityManager->persist($entityVersion);
        } else {
            $entityVersion = $versions[0];
        }
        $entityVersion->setVersion($version);
        $this->entityManager->flush();
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
            if ($table->getShortestName($schema->getName()) == self::VERSION_TABLE) {
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