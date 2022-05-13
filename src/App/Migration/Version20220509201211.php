<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509201211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_shipment DROP FOREIGN KEY FK_D973DA4A19883967');
        $this->addSql('ALTER TABLE sylius_shipping_method_rule DROP FOREIGN KEY FK_88A0EB655F7D6850');
        $this->addSql('ALTER TABLE sylius_shipping_method_translation DROP FOREIGN KEY FK_2B37DB3D2C2AC5D3');
        $this->addSql('CREATE TABLE shop_shipping_method (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', category_requirement INT NOT NULL, calculator VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, position INT NOT NULL, archived_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, taxCategory_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_9CF0FB1B77153098 (code), INDEX IDX_9CF0FB1B12469DE2 (category_id), INDEX IDX_9CF0FB1B2C061291 (taxCategory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_shipping_method ADD CONSTRAINT FK_9CF0FB1B12469DE2 FOREIGN KEY (category_id) REFERENCES sylius_shipping_category (id)');
        $this->addSql('ALTER TABLE shop_shipping_method ADD CONSTRAINT FK_9CF0FB1B2C061291 FOREIGN KEY (taxCategory_id) REFERENCES shop_tax_category (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE sylius_shipping_method');
        $this->addSql('ALTER TABLE shop_shipment ADD CONSTRAINT FK_D973DA4A19883967 FOREIGN KEY (method_id) REFERENCES shop_shipping_method (id)');
        $this->addSql('ALTER TABLE sylius_shipping_method_rule ADD CONSTRAINT FK_88A0EB655F7D6850 FOREIGN KEY (shipping_method_id) REFERENCES shop_shipping_method (id)');
        $this->addSql('ALTER TABLE sylius_shipping_method_translation ADD CONSTRAINT FK_2B37DB3D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_shipping_method (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_shipment DROP FOREIGN KEY FK_D973DA4A19883967');
        $this->addSql('ALTER TABLE sylius_shipping_method_rule DROP FOREIGN KEY FK_88A0EB655F7D6850');
        $this->addSql('ALTER TABLE sylius_shipping_method_translation DROP FOREIGN KEY FK_2B37DB3D2C2AC5D3');
        $this->addSql('CREATE TABLE sylius_shipping_method (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, configuration LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', category_requirement INT NOT NULL, calculator VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_enabled TINYINT(1) NOT NULL, position INT NOT NULL, archived_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_5FB0EE1112469DE2 (category_id), UNIQUE INDEX UNIQ_5FB0EE1177153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sylius_shipping_method ADD CONSTRAINT FK_5FB0EE1112469DE2 FOREIGN KEY (category_id) REFERENCES sylius_shipping_category (id)');
        $this->addSql('DROP TABLE shop_shipping_method');
        $this->addSql('ALTER TABLE shop_shipment ADD CONSTRAINT FK_D973DA4A19883967 FOREIGN KEY (method_id) REFERENCES sylius_shipping_method (id)');
        $this->addSql('ALTER TABLE sylius_shipping_method_rule ADD CONSTRAINT FK_88A0EB655F7D6850 FOREIGN KEY (shipping_method_id) REFERENCES sylius_shipping_method (id)');
        $this->addSql('ALTER TABLE sylius_shipping_method_translation ADD CONSTRAINT FK_2B37DB3D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_shipping_method (id) ON DELETE CASCADE');
    }
}
