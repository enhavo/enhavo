<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012215818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_catalog_promotion (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, priority INT DEFAULT 0 NOT NULL, exclusive TINYINT(1) DEFAULT 0 NOT NULL, state VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1055865077153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_action (id INT AUTO_INCREMENT NOT NULL, catalog_promotion_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_F529624722E2CB5A (catalog_promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_scope (id INT AUTO_INCREMENT NOT NULL, promotion_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_584AA86A139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_catalog_promotion_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, `label` VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_BA065D3C2C2AC5D3 (translatable_id), UNIQUE INDEX sylius_catalog_promotion_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_action ADD CONSTRAINT FK_F529624722E2CB5A FOREIGN KEY (catalog_promotion_id) REFERENCES sylius_catalog_promotion (id)');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_scope ADD CONSTRAINT FK_584AA86A139DF194 FOREIGN KEY (promotion_id) REFERENCES sylius_catalog_promotion (id)');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_translation ADD CONSTRAINT FK_BA065D3C2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_catalog_promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE routing_route DROP condition_expr');
        $this->addSql('ALTER TABLE shop_order_item ADD original_unit_price INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_promotion ADD applies_to_discounted TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE shop_tax_rate ADD start_date DATETIME DEFAULT NULL, ADD end_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP FOREIGN KEY FK_8A053E54B6E62EFA');
        $this->addSql('ALTER TABLE sylius_product_attribute_value ADD CONSTRAINT FK_8A053E54B6E62EFA FOREIGN KEY (attribute_id) REFERENCES shop_product_attribute (id)');
        $this->addSql('ALTER TABLE user_user DROP salt');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_catalog_promotion_action DROP FOREIGN KEY FK_F529624722E2CB5A');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_scope DROP FOREIGN KEY FK_584AA86A139DF194');
        $this->addSql('ALTER TABLE sylius_catalog_promotion_translation DROP FOREIGN KEY FK_BA065D3C2C2AC5D3');
        $this->addSql('DROP TABLE sylius_catalog_promotion');
        $this->addSql('DROP TABLE sylius_catalog_promotion_action');
        $this->addSql('DROP TABLE sylius_catalog_promotion_scope');
        $this->addSql('DROP TABLE sylius_catalog_promotion_translation');
        $this->addSql('ALTER TABLE routing_route ADD condition_expr VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_order_item DROP original_unit_price');
        $this->addSql('ALTER TABLE shop_promotion DROP applies_to_discounted');
        $this->addSql('ALTER TABLE shop_tax_rate DROP start_date, DROP end_date');
        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP FOREIGN KEY FK_8A053E54B6E62EFA');
        $this->addSql('ALTER TABLE sylius_product_attribute_value ADD CONSTRAINT FK_8A053E54B6E62EFA FOREIGN KEY (attribute_id) REFERENCES shop_product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD salt VARCHAR(255) DEFAULT NULL');
    }
}
