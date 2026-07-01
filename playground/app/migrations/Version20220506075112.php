<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220506075112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_user_address (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, shippingAddress_id INT DEFAULT NULL, billingAddress_id INT DEFAULT NULL, INDEX IDX_48261619B1835C8F (shippingAddress_id), INDEX IDX_4826161943656FE6 (billingAddress_id), INDEX IDX_48261619A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_user_address ADD CONSTRAINT FK_48261619B1835C8F FOREIGN KEY (shippingAddress_id) REFERENCES sylius_address (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_user_address ADD CONSTRAINT FK_4826161943656FE6 FOREIGN KEY (billingAddress_id) REFERENCES sylius_address (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_user_address ADD CONSTRAINT FK_48261619A76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD1BCC7F8A8');
        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD1EE45BDBF');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD1BCC7F8A8 FOREIGN KEY (openGraphImage_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD1EE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE block_picture_block DROP FOREIGN KEY FK_7A0C73CE93CB796C');
        $this->addSql('ALTER TABLE block_picture_block ADD CONSTRAINT FK_7A0C73CE93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE block_text_picture_block DROP FOREIGN KEY FK_3B11DE2A93CB796C');
        $this->addSql('ALTER TABLE block_text_picture_block ADD CONSTRAINT FK_3B11DE2A93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460FBCC7F8A8');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460FEE45BDBF');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460FBCC7F8A8 FOREIGN KEY (openGraphImage_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460FEE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFABCC7F8A8');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFABCC7F8A8 FOREIGN KEY (openGraphImage_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE setting_media_value DROP FOREIGN KEY FK_39C6ED5293CB796C');
        $this->addSql('ALTER TABLE setting_media_value ADD CONSTRAINT FK_39C6ED5293CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_order DROP sameAddress');
        $this->addSql('ALTER TABLE slider_slide DROP FOREIGN KEY FK_F45BA2063DA5256D');
        $this->addSql('ALTER TABLE slider_slide ADD CONSTRAINT FK_F45BA2063DA5256D FOREIGN KEY (image_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE translation_translation_file DROP FOREIGN KEY FK_EC79D9C993CB796C');
        $this->addSql('ALTER TABLE translation_translation_file ADD CONSTRAINT FK_EC79D9C993CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE shop_user_address');
        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD1BCC7F8A8');
        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD1EE45BDBF');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD1BCC7F8A8 FOREIGN KEY (openGraphImage_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD1EE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE block_picture_block DROP FOREIGN KEY FK_7A0C73CE93CB796C');
        $this->addSql('ALTER TABLE block_picture_block ADD CONSTRAINT FK_7A0C73CE93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE block_text_picture_block DROP FOREIGN KEY FK_3B11DE2A93CB796C');
        $this->addSql('ALTER TABLE block_text_picture_block ADD CONSTRAINT FK_3B11DE2A93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460FBCC7F8A8');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460FEE45BDBF');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460FBCC7F8A8 FOREIGN KEY (openGraphImage_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460FEE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFABCC7F8A8');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFABCC7F8A8 FOREIGN KEY (openGraphImage_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE setting_media_value DROP FOREIGN KEY FK_39C6ED5293CB796C');
        $this->addSql('ALTER TABLE setting_media_value ADD CONSTRAINT FK_39C6ED5293CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE shop_order ADD sameAddress TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE slider_slide DROP FOREIGN KEY FK_F45BA2063DA5256D');
        $this->addSql('ALTER TABLE slider_slide ADD CONSTRAINT FK_F45BA2063DA5256D FOREIGN KEY (image_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE translation_translation_file DROP FOREIGN KEY FK_EC79D9C993CB796C');
        $this->addSql('ALTER TABLE translation_translation_file ADD CONSTRAINT FK_EC79D9C993CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
