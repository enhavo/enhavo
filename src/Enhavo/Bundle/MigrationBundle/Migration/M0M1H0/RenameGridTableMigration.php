<?php

namespace Enhavo\Bundle\MigrationBundle\Migration\M0M1H0;

use Enhavo\Bundle\MigrationBundle\Migration\AbstractMigration;
use Enhavo\Bundle\MigrationBundle\Database\DatabaseModifier;

class RenameGridTableMigration extends AbstractMigration
{
    /**
     * @var DatabaseModifier
     */
    protected $databaseModifier;

    public function migrate()
    {
        $this->databaseModifier = $this->get('enhavo_migration.database_modifier');

        $renameTables = [
            'content_column' => 'grid_column',
            'content_container' => 'grid_container',
            'content_content' => 'grid_grid',
            'content_gallery_files' => 'grid_item_gallery_files',
            'content_item' => 'grid_item',
            'content_item_download' => 'grid_item_download',
            'content_type_cite_text' => 'grid_item_cite_text',
            'content_type_gallery' => 'grid_item_gallery',
            'content_type_picture' => 'grid_item_picture',
            'content_type_picture_picture' => 'grid_item_picture_picture',
            'content_type_text' => 'grid_item_text',
            'content_type_text_picture' => 'grid_item_text_picture',
            'content_type_text_text' => 'grid_item_text_text'
        ];

        foreach($renameTables as $source => $target) {
            $this->databaseModifier->renameTable($source, $target);
        }

        //ToDo: Rename content_id columns to grid_id columns
    }
}
