<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510131618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_payment DROP FOREIGN KEY FK_6E1BC42719883967');
        $this->addSql('ALTER TABLE shop_shipment DROP FOREIGN KEY FK_D973DA4A19883967');
        $this->addSql('ALTER TABLE sylius_payment_method_translation DROP FOREIGN KEY FK_966BE3A12C2AC5D3');
        $this->addSql('ALTER TABLE sylius_shipping_method_translation DROP FOREIGN KEY FK_2B37DB3D2C2AC5D3');
        $this->addSql('CREATE TABLE shop_payment_method (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, environment VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, gatewayName VARCHAR(255) NOT NULL, gatewayType VARCHAR(255) NOT NULL, factoryName VARCHAR(255) NOT NULL, config LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_EBC0A0F077153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE sylius_payment_method');
        $this->addSql('DROP TABLE sylius_shipment_unit');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC42719883967 FOREIGN KEY (method_id) REFERENCES shop_payment_method (id)');
        $this->addSql('ALTER TABLE shop_shipment ADD CONSTRAINT FK_D973DA4A19883967 FOREIGN KEY (method_id) REFERENCES shop_shipping_method (id)');
        $this->addSql('ALTER TABLE sylius_payment_method_translation ADD CONSTRAINT FK_966BE3A12C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_payment_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_shipping_method_translation ADD CONSTRAINT FK_2B37DB3D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_shipping_method (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_payment DROP FOREIGN KEY FK_6E1BC42719883967');
        $this->addSql('ALTER TABLE shop_payment DROP FOREIGN KEY FK_D973DA4A19883967');
        $this->addSql('ALTER TABLE sylius_payment_method_translation DROP FOREIGN KEY FK_966BE3A12C2AC5D3');
        $this->addSql('ALTER TABLE sylius_shipping_method_translation DROP FOREIGN KEY FK_2B37DB3D2C2AC5D3');
        $this->addSql('CREATE TABLE sylius_payment_method (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, environment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, is_enabled TINYINT(1) NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A75B0B0D77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sylius_shipment_unit (id INT AUTO_INCREMENT NOT NULL, shipment_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_2EC26E237BE036FC (shipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sylius_shipment_unit ADD CONSTRAINT FK_2EC26E237BE036FC FOREIGN KEY (shipment_id) REFERENCES shop_shipment (id)');
        $this->addSql('DROP TABLE shop_payment_method');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC42719883967 FOREIGN KEY (method_id) REFERENCES sylius_payment_method (id)');
        $this->addSql('ALTER TABLE shop_shipment DROP FOREIGN KEY FK_D973DA4A19883967');
        $this->addSql('ALTER TABLE sylius_payment_method_translation ADD CONSTRAINT FK_966BE3A12C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_payment_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_shipping_method_translation DROP FOREIGN KEY FK_2B37DB3D2C2AC5D3');
    }
}
