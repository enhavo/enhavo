<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410204447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_payment (id INT AUTO_INCREMENT NOT NULL, method_id INT DEFAULT NULL, order_id INT DEFAULT NULL, currency_code VARCHAR(3) NOT NULL, amount INT NOT NULL, state VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_6E1BC42719883967 (method_id), INDEX IDX_6E1BC4278D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC42719883967 FOREIGN KEY (method_id) REFERENCES sylius_payment_method (id)');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC4278D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE sylius_payment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_payment (id INT AUTO_INCREMENT NOT NULL, method_id INT DEFAULT NULL, order_id INT DEFAULT NULL, currency_code VARCHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, amount INT NOT NULL, state VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, details LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, discr VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D9191BD48D9F6D38 (order_id), INDEX IDX_D9191BD419883967 (method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sylius_payment ADD CONSTRAINT FK_D9191BD419883967 FOREIGN KEY (method_id) REFERENCES sylius_payment_method (id)');
        $this->addSql('ALTER TABLE sylius_payment ADD CONSTRAINT FK_D9191BD48D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE shop_payment');
    }
}
