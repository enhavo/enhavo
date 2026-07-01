<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411085532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_article DROP INDEX UNIQ_EFE84AD1BCC7F8A8, ADD INDEX IDX_EFE84AD1BCC7F8A8 (openGraphImage_id)');
        $this->addSql('ALTER TABLE article_article DROP INDEX UNIQ_EFE84AD1EE45BDBF, ADD INDEX IDX_EFE84AD1EE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE block_picture_block DROP INDEX UNIQ_7A0C73CE93CB796C, ADD INDEX IDX_7A0C73CE93CB796C (file_id)');
        $this->addSql('ALTER TABLE block_text_picture_block DROP INDEX UNIQ_3B11DE2A93CB796C, ADD INDEX IDX_3B11DE2A93CB796C (file_id)');
        $this->addSql('ALTER TABLE calendar_appointment DROP INDEX UNIQ_8EC4460FBCC7F8A8, ADD INDEX IDX_8EC4460FBCC7F8A8 (openGraphImage_id)');
        $this->addSql('ALTER TABLE calendar_appointment DROP INDEX UNIQ_8EC4460FEE45BDBF, ADD INDEX IDX_8EC4460FEE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE page_page DROP INDEX UNIQ_93CEAAFABCC7F8A8, ADD INDEX IDX_93CEAAFABCC7F8A8 (openGraphImage_id)');
        $this->addSql('ALTER TABLE setting_media_value DROP INDEX UNIQ_39C6ED5293CB796C, ADD INDEX IDX_39C6ED5293CB796C (file_id)');
        $this->addSql('ALTER TABLE slider_slide DROP INDEX UNIQ_F45BA2063DA5256D, ADD INDEX IDX_F45BA2063DA5256D (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_article DROP INDEX IDX_EFE84AD1BCC7F8A8, ADD UNIQUE INDEX UNIQ_EFE84AD1BCC7F8A8 (openGraphImage_id)');
        $this->addSql('ALTER TABLE article_article DROP INDEX IDX_EFE84AD1EE45BDBF, ADD UNIQUE INDEX UNIQ_EFE84AD1EE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE block_picture_block DROP INDEX IDX_7A0C73CE93CB796C, ADD UNIQUE INDEX UNIQ_7A0C73CE93CB796C (file_id)');
        $this->addSql('ALTER TABLE block_text_picture_block DROP INDEX IDX_3B11DE2A93CB796C, ADD UNIQUE INDEX UNIQ_3B11DE2A93CB796C (file_id)');
        $this->addSql('ALTER TABLE calendar_appointment DROP INDEX IDX_8EC4460FBCC7F8A8, ADD UNIQUE INDEX UNIQ_8EC4460FBCC7F8A8 (openGraphImage_id)');
        $this->addSql('ALTER TABLE calendar_appointment DROP INDEX IDX_8EC4460FEE45BDBF, ADD UNIQUE INDEX UNIQ_8EC4460FEE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE page_page DROP INDEX IDX_93CEAAFABCC7F8A8, ADD UNIQUE INDEX UNIQ_93CEAAFABCC7F8A8 (openGraphImage_id)');
        $this->addSql('ALTER TABLE setting_media_value DROP INDEX IDX_39C6ED5293CB796C, ADD UNIQUE INDEX UNIQ_39C6ED5293CB796C (file_id)');
        $this->addSql('ALTER TABLE slider_slide DROP INDEX IDX_F45BA2063DA5256D, ADD UNIQUE INDEX UNIQ_F45BA2063DA5256D (image_id)');
    }
}
