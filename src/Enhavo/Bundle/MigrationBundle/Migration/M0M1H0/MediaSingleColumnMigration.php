<?php

namespace Enhavo\Bundle\MigrationBundle\Migration\M0M1H0;

use Enhavo\Bundle\MigrationBundle\Migration\AbstractMigration;
use Enhavo\Bundle\MigrationBundle\Database\DatabaseModifier;

class MediaSingleColumnMigration extends AbstractMigration
{
    /**
     * @var DatabaseModifier
     */
    protected $databaseModifier;

    public function migrate()
    {
        $this->databaseModifier = $this->get('enhavo_migration.database_modifier');

        // Add new columns
        $this->databaseModifier->addColumnsToTableIfExists('article_article', array('picture_id'));
        $this->databaseModifier->addColumnsToTableIfExists('page_page', array('picture_id'));
        $this->databaseModifier->addColumnsToTableIfExists('category_category', array('picture_id'));
        $this->databaseModifier->addColumnsToTableIfExists('content_type_picture', array('file_id'));
        $this->databaseModifier->addColumnsToTableIfExists('content_type_text_picture', array('file_id'));
        $this->databaseModifier->addColumnsToTableIfExists('content_type_picture_picture', array('fileLeft_id', 'fileRight_id'));
        $this->databaseModifier->addColumnsToTableIfExists('content_item_download', array('file_id'));
        $this->databaseModifier->addColumnsToTableIfExists('download_download', array('file_id'));
        $this->databaseModifier->addColumnsToTableIfExists('slider_slide', array('image_id'));
        $this->databaseModifier->addColumnsToTableIfExists('calendar_appointment', array('picture_id'));

        // Copy connections
        $this->databaseModifier->copyConnectionsIfTableExists('article_article', 'id', 'picture_id', 'article_article_picture', 'article_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('page_page', 'id', 'picture_id', 'page_page_picture', 'page_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('category_category', 'id', 'picture_id', 'category_category_picture', 'category_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('content_type_picture', 'id', 'file_id', 'content_picture_files', 'picture_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('content_type_text_picture', 'id', 'file_id', 'content_textpicture_files', 'textpicture_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('content_type_picture_picture', 'id', 'fileLeft_id', 'content_pictureleft_files', 'picturepicture_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('content_type_picture_picture', 'id', 'fileRight_id', 'content_pictureright_files', 'picturepicture_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('content_item_download', 'id', 'file_id', 'content_item_download_file', 'download_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('download_download', 'id', 'file_id', 'download_download_file', 'download_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('slider_slide', 'id', 'image_id', 'slider_slide_image', 'slider_id', 'file_id');
        $this->databaseModifier->copyConnectionsIfTableExists('calendar_appointment', 'id', 'picture_id', 'calendar_appointment_picture', 'appointment_id', 'file_id');

        // Drop old join tables
        $this->databaseModifier->dropTableIfExists('article_article_picture');
        $this->databaseModifier->dropTableIfExists('page_page_picture');
        $this->databaseModifier->dropTableIfExists('category_category_picture');
        $this->databaseModifier->dropTableIfExists('content_picture_files');
        $this->databaseModifier->dropTableIfExists('content_textpicture_files');
        $this->databaseModifier->dropTableIfExists('content_pictureleft_files');
        $this->databaseModifier->dropTableIfExists('content_pictureright_files');
        $this->databaseModifier->dropTableIfExists('content_item_download_file');
        $this->databaseModifier->dropTableIfExists('download_download_file');
        $this->databaseModifier->dropTableIfExists('slider_slide_image');
        $this->databaseModifier->dropTableIfExists('calendar_appointment_picture');
    }
}
