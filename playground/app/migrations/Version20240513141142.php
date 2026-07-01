<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513141142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_product CHANGE height height DOUBLE PRECISION DEFAULT NULL, CHANGE width width DOUBLE PRECISION DEFAULT NULL, CHANGE depth depth DOUBLE PRECISION DEFAULT NULL, CHANGE volume volume DOUBLE PRECISION DEFAULT NULL, CHANGE weight weight DOUBLE PRECISION DEFAULT NULL, CHANGE shippingCategory shippingCategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D07944874F6AB213 FOREIGN KEY (shippingCategory_id) REFERENCES sylius_shipping_category (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D07944874F6AB213 ON shop_product (shippingCategory_id)');
        $this->addSql('ALTER TABLE shop_product_variant CHANGE height height DOUBLE PRECISION DEFAULT NULL, CHANGE width width DOUBLE PRECISION DEFAULT NULL, CHANGE depth depth DOUBLE PRECISION DEFAULT NULL, CHANGE volume volume DOUBLE PRECISION DEFAULT NULL, CHANGE weight weight DOUBLE PRECISION DEFAULT NULL, CHANGE shippingCategory shippingCategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A0294F6AB213 FOREIGN KEY (shippingCategory_id) REFERENCES sylius_shipping_category (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C969A0294F6AB213 ON shop_product_variant (shippingCategory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D07944874F6AB213');
        $this->addSql('DROP INDEX IDX_D07944874F6AB213 ON shop_product');
        $this->addSql('ALTER TABLE shop_product CHANGE height height INT DEFAULT NULL, CHANGE width width INT DEFAULT NULL, CHANGE depth depth INT DEFAULT NULL, CHANGE volume volume INT DEFAULT NULL, CHANGE weight weight INT DEFAULT NULL, CHANGE shippingCategory_id shippingCategory INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A0294F6AB213');
        $this->addSql('DROP INDEX IDX_C969A0294F6AB213 ON shop_product_variant');
        $this->addSql('ALTER TABLE shop_product_variant CHANGE height height INT DEFAULT NULL, CHANGE width width INT DEFAULT NULL, CHANGE depth depth INT DEFAULT NULL, CHANGE volume volume INT DEFAULT NULL, CHANGE weight weight INT DEFAULT NULL, CHANGE shippingCategory_id shippingCategory INT DEFAULT NULL');
    }
}
