<?php

/**
 * RemoveMigrationDatabaseVersionMigration.php
 *
 * @since 27/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MigrationBundle\Migration\M0M2H0;

use Enhavo\Bundle\MigrationBundle\Migration\AbstractMigration;

class RemoveMigrationDatabaseVersionMigration extends AbstractMigration
{
    public function migrate()
    {
        $this->executeSql("DROP TABLE `migration_database_version`;");
    }
}