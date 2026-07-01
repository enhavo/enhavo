<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220409144949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CA43656FE6');
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CA7BE036FC');
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CA4C3A3BB');
        $this->addSql('DROP INDEX UNIQ_323FC9CA7BE036FC ON shop_order');
        $this->addSql('DROP INDEX IDX_323FC9CA4C3A3BB ON shop_order');
        $this->addSql('DROP INDEX IDX_323FC9CA43656FE6 ON shop_order');
        $this->addSql('ALTER TABLE shop_order DROP shipment_id, DROP payment_id, DROP checkoutState, DROP differentBillingAddress, DROP orderedAt, DROP notice, DROP billingAddress_id');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F4584665A');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F4584665A FOREIGN KEY (product_id) REFERENCES shop_product_variant (id)');
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D0794487C6CD2B2B');
        $this->addSql('DROP INDEX IDX_D0794487C6CD2B2B ON shop_product');
        $this->addSql('ALTER TABLE shop_product CHANGE taxRate_id taxCategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D07944872C061291 FOREIGN KEY (taxCategory_id) REFERENCES shop_tax_category (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D07944872C061291 ON shop_product (taxCategory_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX UNIQ_659142E64584665A, ADD INDEX IDX_659142E64584665A (product_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX UNIQ_659142E6EE45BDBF, ADD INDEX IDX_659142E6EE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A029C6CD2B2B');
        $this->addSql('DROP INDEX IDX_C969A029C6CD2B2B ON shop_product_variant');
        $this->addSql('ALTER TABLE shop_product_variant CHANGE taxRate_id taxCategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A0292C061291 FOREIGN KEY (taxCategory_id) REFERENCES shop_tax_category (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C969A0292C061291 ON shop_product_variant (taxCategory_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX UNIQ_D6AFF9EA80EF684, ADD INDEX IDX_D6AFF9EA80EF684 (product_variant_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX UNIQ_D6AFF9EEE45BDBF, ADD INDEX IDX_D6AFF9EEE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE shop_shipment ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_shipment ADD CONSTRAINT FK_D973DA4A8D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D973DA4A8D9F6D38 ON shop_shipment (order_id)');
        $this->addSql('ALTER TABLE sylius_payment ADD order_id INT DEFAULT NULL, ADD discr VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sylius_payment ADD CONSTRAINT FK_D9191BD48D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D9191BD48D9F6D38 ON sylius_payment (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_order ADD shipment_id INT DEFAULT NULL, ADD payment_id INT DEFAULT NULL, ADD checkoutState VARCHAR(255) DEFAULT NULL, ADD differentBillingAddress TINYINT(1) DEFAULT NULL, ADD orderedAt DATETIME DEFAULT NULL, ADD notice LONGTEXT DEFAULT NULL, ADD billingAddress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA43656FE6 FOREIGN KEY (billingAddress_id) REFERENCES sylius_address (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA7BE036FC FOREIGN KEY (shipment_id) REFERENCES shop_shipment (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA4C3A3BB FOREIGN KEY (payment_id) REFERENCES sylius_payment (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_323FC9CA7BE036FC ON shop_order (shipment_id)');
        $this->addSql('CREATE INDEX IDX_323FC9CA4C3A3BB ON shop_order (payment_id)');
        $this->addSql('CREATE INDEX IDX_323FC9CA43656FE6 ON shop_order (billingAddress_id)');
        $this->addSql('ALTER TABLE shop_order_item DROP FOREIGN KEY FK_2899F22F4584665A');
        $this->addSql('ALTER TABLE shop_order_item ADD CONSTRAINT FK_2899F22F4584665A FOREIGN KEY (product_id) REFERENCES shop_product (id)');
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D07944872C061291');
        $this->addSql('DROP INDEX IDX_D07944872C061291 ON shop_product');
        $this->addSql('ALTER TABLE shop_product CHANGE taxCategory_id taxRate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D0794487C6CD2B2B FOREIGN KEY (taxRate_id) REFERENCES shop_tax_rate (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D0794487C6CD2B2B ON shop_product (taxRate_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX IDX_659142E64584665A, ADD UNIQUE INDEX UNIQ_659142E64584665A (product_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX IDX_659142E6EE45BDBF, ADD UNIQUE INDEX UNIQ_659142E6EE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A0292C061291');
        $this->addSql('DROP INDEX IDX_C969A0292C061291 ON shop_product_variant');
        $this->addSql('ALTER TABLE shop_product_variant CHANGE taxCategory_id taxRate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A029C6CD2B2B FOREIGN KEY (taxRate_id) REFERENCES shop_tax_rate (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C969A029C6CD2B2B ON shop_product_variant (taxRate_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX IDX_D6AFF9EA80EF684, ADD UNIQUE INDEX UNIQ_D6AFF9EA80EF684 (product_variant_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX IDX_D6AFF9EEE45BDBF, ADD UNIQUE INDEX UNIQ_D6AFF9EEE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE shop_shipment DROP FOREIGN KEY FK_D973DA4A8D9F6D38');
        $this->addSql('DROP INDEX IDX_D973DA4A8D9F6D38 ON shop_shipment');
        $this->addSql('ALTER TABLE shop_shipment DROP order_id');
        $this->addSql('ALTER TABLE sylius_payment DROP FOREIGN KEY FK_D9191BD48D9F6D38');
        $this->addSql('DROP INDEX IDX_D9191BD48D9F6D38 ON sylius_payment');
        $this->addSql('ALTER TABLE sylius_payment DROP order_id, DROP discr');
    }
}
