<?php
/**
 * Migrator.php
 *
 * @since 25/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MigrationBundle\Migration;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\DependencyInjection\EnhavoAppExtension;
use Enhavo\Bundle\MigrationBundle\Database\VersionAccessor;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class Migrator
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var array
     */
    protected $migrations;

    /**
     * @var array
     */
    protected $container;

    /**
     * @var VersionAccessor
     */
    protected $versionAccessor;

    public function __construct(
        EntityManagerInterface $em,
        KernelInterface $kernel,
        ContainerInterface $container,
        VersionAccessor $versionAccessor,
        $migrationConfigPath
    )
    {
        $this->em = $em;
        $this->migrations = Yaml::parse($kernel->locateResource($migrationConfigPath));
        $this->container = $container;
        $this->versionAccessor = $versionAccessor;
    }

    public function run($from = null, $to = null)
    {
        $migrationsList = $this->getMigrationList($from, $to);
        foreach($migrationsList as $migration) {
            $migrationClass = new $migration;
            if($migrationClass instanceof ContainerAwareInterface) {
                $migrationClass->setContainer($this->container);
            }
            if($migrationClass instanceof Migration) {
                $migrationClass->migrate();
            }
        }

        if($to) {
            $updatedToVersion = $to;
        } else {
            $updatedToVersion = $this->getUpdatedToVersion();
        }

        $this->updateVersion($updatedToVersion);
    }

    protected function getMigrationList($from = null, $to = null)
    {
        if($from) {
            $currentVersion = $from;
        } else {
            $currentVersion = $this->getCurrentVersion();
        }

        if($to) {
            $updatedToVersion = $to;
        } else {
            $updatedToVersion = $this->getUpdatedToVersion();
        }

        if(version_compare($currentVersion, $updatedToVersion, '=')) {
            return [];
        }

        $list = [];
        foreach($this->migrations as $version => $migrations) {
            if(version_compare($version, $currentVersion, '>') && version_compare($version, $updatedToVersion, '<=')) {
                foreach($migrations as $migration) {
                    $list[] = $migration;
                }
            }
        }

        return $list;
    }

    protected function getCurrentVersion()
    {
        return $this->versionAccessor->getVersion();
    }

    protected function getUpdatedToVersion()
    {
        return EnhavoAppExtension::VERSION;
    }

    protected function updateVersion($version)
    {
        $this->versionAccessor->setVersion($version);
    }
}