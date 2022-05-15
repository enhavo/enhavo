<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513140252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_payment DROP FOREIGN KEY FK_6E1BC42719883967');
        $this->addSql('ALTER TABLE sylius_payment_method_translation DROP FOREIGN KEY FK_966BE3A12C2AC5D3');
        $this->addSql('CREATE TABLE payment_gateway_config (id INT AUTO_INCREMENT NOT NULL, gateway_name VARCHAR(255) NOT NULL, factory_name VARCHAR(255) NOT NULL, config LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_payment (id INT AUTO_INCREMENT NOT NULL, method_id INT DEFAULT NULL, order_id INT DEFAULT NULL, currency_code VARCHAR(3) NOT NULL, amount INT NOT NULL, state VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_74618AE719883967 (method_id), INDEX IDX_74618AE78D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_payment_method (id INT AUTO_INCREMENT NOT NULL, gateway_config_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, environment VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_9E491BF277153098 (code), INDEX IDX_9E491BF2F23D6140 (gateway_config_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_payment_method_translation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, instructions LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_payment_security_token (hash VARCHAR(255) NOT NULL, details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', after_url LONGTEXT DEFAULT NULL, target_url LONGTEXT NOT NULL, gateway_name VARCHAR(255) NOT NULL, PRIMARY KEY(hash)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_payment ADD CONSTRAINT FK_74618AE719883967 FOREIGN KEY (method_id) REFERENCES payment_payment_method (id)');
        $this->addSql('ALTER TABLE payment_payment ADD CONSTRAINT FK_74618AE78D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE payment_payment_method ADD CONSTRAINT FK_9E491BF2F23D6140 FOREIGN KEY (gateway_config_id) REFERENCES payment_gateway_config (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE shop_payment');
        $this->addSql('DROP TABLE shop_payment_method');
        $this->addSql('ALTER TABLE sylius_payment_method_translation ADD CONSTRAINT FK_966BE3A12C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES payment_payment_method (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_payment_method DROP FOREIGN KEY FK_9E491BF2F23D6140');
        $this->addSql('ALTER TABLE payment_payment DROP FOREIGN KEY FK_74618AE719883967');
        $this->addSql('ALTER TABLE sylius_payment_method_translation DROP FOREIGN KEY FK_966BE3A12C2AC5D3');
        $this->addSql('CREATE TABLE shop_payment (id INT AUTO_INCREMENT NOT NULL, method_id INT DEFAULT NULL, order_id INT DEFAULT NULL, currency_code VARCHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, amount INT NOT NULL, state VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, details LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_6E1BC4278D9F6D38 (order_id), INDEX IDX_6E1BC42719883967 (method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE shop_payment_method (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, environment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, is_enabled TINYINT(1) NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, gatewayName VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, gatewayType VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, factoryName VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, config LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_EBC0A0F077153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC42719883967 FOREIGN KEY (method_id) REFERENCES shop_payment_method (id)');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC4278D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE payment_gateway_config');
        $this->addSql('DROP TABLE payment_payment');
        $this->addSql('DROP TABLE payment_payment_method');
        $this->addSql('DROP TABLE payment_payment_method_translation');
        $this->addSql('DROP TABLE payment_payment_security_token');
        $this->addSql('ALTER TABLE sylius_payment_method_translation ADD CONSTRAINT FK_966BE3A12C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_payment_method (id) ON DELETE CASCADE');
    }
}
