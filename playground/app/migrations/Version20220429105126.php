<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429105126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE shop_user_address');
        $this->addSql('ALTER TABLE shop_order ADD sameAddress TINYINT(1) DEFAULT NULL, ADD billingAddress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_order ADD CONSTRAINT FK_323FC9CA43656FE6 FOREIGN KEY (billingAddress_id) REFERENCES sylius_address (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_323FC9CA43656FE6 ON shop_order (billingAddress_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_user_address (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, differentBillingAddress TINYINT(1) DEFAULT NULL, shippingAddress_id INT DEFAULT NULL, billingAddress_id INT DEFAULT NULL, INDEX IDX_48261619B1835C8F (shippingAddress_id), UNIQUE INDEX UNIQ_48261619A76ED395 (user_id), INDEX IDX_4826161943656FE6 (billingAddress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE shop_user_address ADD CONSTRAINT FK_4826161943656FE6 FOREIGN KEY (billingAddress_id) REFERENCES sylius_address (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_user_address ADD CONSTRAINT FK_48261619B1835C8F FOREIGN KEY (shippingAddress_id) REFERENCES sylius_address (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_user_address ADD CONSTRAINT FK_48261619A76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id)');
        $this->addSql('ALTER TABLE shop_order DROP FOREIGN KEY FK_323FC9CA43656FE6');
        $this->addSql('DROP INDEX IDX_323FC9CA43656FE6 ON shop_order');
        $this->addSql('ALTER TABLE shop_order DROP sameAddress, DROP billingAddress_id');
    }
}
