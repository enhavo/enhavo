<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721072030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_order_voucher (order_id INT NOT NULL, voucher_id INT NOT NULL, INDEX IDX_FBF49B9C8D9F6D38 (order_id), INDEX IDX_FBF49B9C28AA1B6F (voucher_id), PRIMARY KEY(order_id, voucher_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_voucher_redemption (id INT AUTO_INCREMENT NOT NULL, voucher_id INT DEFAULT NULL, order_id INT DEFAULT NULL, amount INT DEFAULT NULL, INDEX IDX_C02A7B4728AA1B6F (voucher_id), INDEX IDX_C02A7B478D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_order_voucher ADD CONSTRAINT FK_FBF49B9C8D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id)');
        $this->addSql('ALTER TABLE shop_order_voucher ADD CONSTRAINT FK_FBF49B9C28AA1B6F FOREIGN KEY (voucher_id) REFERENCES shop_voucher (id)');
        $this->addSql('ALTER TABLE shop_voucher_redemption ADD CONSTRAINT FK_C02A7B4728AA1B6F FOREIGN KEY (voucher_id) REFERENCES shop_voucher (id)');
        $this->addSql('ALTER TABLE shop_voucher_redemption ADD CONSTRAINT FK_C02A7B478D9F6D38 FOREIGN KEY (order_id) REFERENCES shop_order (id)');
        $this->addSql('ALTER TABLE shop_voucher DROP FOREIGN KEY FK_10A1E5F266C5951B');
        $this->addSql('DROP INDEX UNIQ_10A1E5F266C5951B ON shop_voucher');
        $this->addSql('ALTER TABLE shop_voucher ADD enabled TINYINT(1) NOT NULL, ADD partialRedeemable TINYINT(1) NOT NULL, DROP coupon_id, DROP redeemAmount, CHANGE redeemedAt expiredAt DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE shop_order_voucher');
        $this->addSql('DROP TABLE shop_voucher_redemption');
        $this->addSql('ALTER TABLE shop_voucher ADD coupon_id INT DEFAULT NULL, ADD redeemAmount INT DEFAULT NULL, DROP enabled, DROP partialRedeemable, CHANGE expiredAt redeemedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_voucher ADD CONSTRAINT FK_10A1E5F266C5951B FOREIGN KEY (coupon_id) REFERENCES sylius_promotion_coupon (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10A1E5F266C5951B ON shop_voucher (coupon_id)');
    }
}
