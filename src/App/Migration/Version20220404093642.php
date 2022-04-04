<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404093642 extends AbstractMigration
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
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F4584665A');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F4584665A FOREIGN KEY (product_id) REFERENCES shop_product_variant (id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX UNIQ_659142E64584665A, ADD INDEX IDX_659142E64584665A (product_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX UNIQ_659142E6EE45BDBF, ADD INDEX IDX_659142E6EE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX UNIQ_D6AFF9EA80EF684, ADD INDEX IDX_D6AFF9EA80EF684 (product_variant_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX UNIQ_D6AFF9EEE45BDBF, ADD INDEX IDX_D6AFF9EEE45BDBF (picture_id)');
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
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F4584665A');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F4584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX IDX_659142E64584665A, ADD UNIQUE INDEX UNIQ_659142E64584665A (product_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX IDX_659142E6EE45BDBF, ADD UNIQUE INDEX UNIQ_659142E6EE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX IDX_D6AFF9EA80EF684, ADD UNIQUE INDEX UNIQ_D6AFF9EA80EF684 (product_variant_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX IDX_D6AFF9EEE45BDBF, ADD UNIQUE INDEX UNIQ_D6AFF9EEE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE slider_slide DROP INDEX IDX_F45BA2063DA5256D, ADD UNIQUE INDEX UNIQ_F45BA2063DA5256D (image_id)');
    }
}
