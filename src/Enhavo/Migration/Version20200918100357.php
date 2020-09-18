<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918100357 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shop_product_variant ADD picture_id INT DEFAULT NULL, ADD title VARCHAR(255) NOT NULL, ADD price INT DEFAULT NULL, ADD height INT DEFAULT NULL, ADD width INT DEFAULT NULL, ADD depth INT DEFAULT NULL, ADD volume INT DEFAULT NULL, ADD weight INT DEFAULT NULL, ADD shippingCategory INT DEFAULT NULL, ADD taxRate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A029EE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A029C6CD2B2B FOREIGN KEY (taxRate_id) REFERENCES shop_tax_rate (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C969A029EE45BDBF ON shop_product_variant (picture_id)');
        $this->addSql('CREATE INDEX IDX_C969A029C6CD2B2B ON shop_product_variant (taxRate_id)');
        $this->addSql('ALTER TABLE shop_product ADD shippingCategory INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shop_product DROP shippingCategory');
        $this->addSql('ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A029EE45BDBF');
        $this->addSql('ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A029C6CD2B2B');
        $this->addSql('DROP INDEX IDX_C969A029EE45BDBF ON shop_product_variant');
        $this->addSql('DROP INDEX IDX_C969A029C6CD2B2B ON shop_product_variant');
        $this->addSql('ALTER TABLE shop_product_variant DROP picture_id, DROP title, DROP price, DROP height, DROP width, DROP depth, DROP volume, DROP weight, DROP shippingCategory, DROP taxRate_id');
    }
}
