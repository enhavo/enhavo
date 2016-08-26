<?php

/**
 * AbstractMigration.php
 *
 * @since 25/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MigrationBundle\Migration;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractMigration implements Migration, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    abstract function migrate();

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param $id
     * @return object
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    public function executeSql($sql)
    {
        $this->get('enhavo_migration.database_modifier')->executeQuery($sql);
    }

    public function getDatabaseModifier()
    {
        return $this->get('enhavo_migration.database_modifier');
    }

    public function getManager()
    {
        return $this->container->get('doctrine.orm.default_entity_manager');
    }
}