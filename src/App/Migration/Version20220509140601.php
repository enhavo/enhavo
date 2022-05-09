<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509140601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_adjustment DROP FOREIGN KEY FK_ACA6E0F2F720C233');
        $this->addSql('CREATE TABLE shop_order_item_unit (id INT AUTO_INCREMENT NOT NULL, order_item_id INT NOT NULL, shipment_id INT DEFAULT NULL, adjustments_total INT NOT NULL, INDEX IDX_41FF3764E415FB15 (order_item_id), INDEX IDX_41FF37647BE036FC (shipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_order_item_unit ADD CONSTRAINT FK_41FF3764E415FB15 FOREIGN KEY (order_item_id) REFERENCES shop_order_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_order_item_unit ADD CONSTRAINT FK_41FF37647BE036FC FOREIGN KEY (shipment_id) REFERENCES shop_shipment (id)');
        $this->addSql('DROP TABLE sylius_order_item_unit');
        $this->addSql('ALTER TABLE sylius_adjustment ADD CONSTRAINT FK_ACA6E0F2F720C233 FOREIGN KEY (order_item_unit_id) REFERENCES shop_order_item_unit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_adjustment DROP FOREIGN KEY FK_ACA6E0F2F720C233');
        $this->addSql('CREATE TABLE sylius_order_item_unit (id INT AUTO_INCREMENT NOT NULL, order_item_id INT NOT NULL, adjustments_total INT NOT NULL, INDEX IDX_82BF226EE415FB15 (order_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sylius_order_item_unit ADD CONSTRAINT FK_82BF226EE415FB15 FOREIGN KEY (order_item_id) REFERENCES shop_order_item (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE shop_order_item_unit');
        $this->addSql('ALTER TABLE sylius_adjustment ADD CONSTRAINT FK_ACA6E0F2F720C233 FOREIGN KEY (order_item_unit_id) REFERENCES sylius_order_item_unit (id) ON DELETE CASCADE');
    }
}
