<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401121841 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_person (id INT AUTO_INCREMENT NOT NULL, birthday DATETIME DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_shipping_method_rule (id INT AUTO_INCREMENT NOT NULL, shipping_method_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_88A0EB655F7D6850 (shipping_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_shipping_method_rule ADD CONSTRAINT FK_88A0EB655F7D6850 FOREIGN KEY (shipping_method_id) REFERENCES sylius_shipping_method (id)');
        $this->addSql('ALTER TABLE shop_product_variant ADD enabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE shop_shipment ADD shipped_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE app_person');
        $this->addSql('DROP TABLE sylius_shipping_method_rule');
        $this->addSql('ALTER TABLE shop_product_variant DROP enabled');
        $this->addSql('ALTER TABLE shop_shipment DROP shipped_at');
    }
}
