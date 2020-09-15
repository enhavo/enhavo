<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915085450 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_association_product DROP FOREIGN KEY FK_A427B983EFB9C8A5');
        $this->addSql('ALTER TABLE sylius_product_association DROP FOREIGN KEY FK_48E9CDABB1E1C39');
        $this->addSql('ALTER TABLE sylius_product_association_type_translation DROP FOREIGN KEY FK_4F618E52C2AC5D3');
        $this->addSql('CREATE TABLE shop_product_association_type (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BEBA3DFE77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE sylius_product_association');
        $this->addSql('DROP TABLE sylius_product_association_type');
        $this->addSql('ALTER TABLE shop_product_association ADD association_type_id INT NOT NULL, ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE shop_product_association ADD CONSTRAINT FK_CFAD905BB1E1C39 FOREIGN KEY (association_type_id) REFERENCES shop_product_association_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_association ADD CONSTRAINT FK_CFAD905B4584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_CFAD905BB1E1C39 ON shop_product_association (association_type_id)');
        $this->addSql('CREATE INDEX IDX_CFAD905B4584665A ON shop_product_association (product_id)');
        $this->addSql('CREATE UNIQUE INDEX product_association_idx ON shop_product_association (product_id, association_type_id)');
        $this->addSql('ALTER TABLE sylius_product_association_product ADD CONSTRAINT FK_A427B983EFB9C8A5 FOREIGN KEY (association_id) REFERENCES shop_product_association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_association_type_translation ADD CONSTRAINT FK_4F618E52C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_product_association_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shop_product_association DROP FOREIGN KEY FK_CFAD905BB1E1C39');
        $this->addSql('ALTER TABLE sylius_product_association_type_translation DROP FOREIGN KEY FK_4F618E52C2AC5D3');
        $this->addSql('CREATE TABLE sylius_product_association (id INT AUTO_INCREMENT NOT NULL, association_type_id INT NOT NULL, product_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_48E9CDAB4584665A (product_id), UNIQUE INDEX product_association_idx (product_id, association_type_id), INDEX IDX_48E9CDABB1E1C39 (association_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sylius_product_association_type (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_CCB8914C77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sylius_product_association ADD CONSTRAINT FK_48E9CDAB4584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_association ADD CONSTRAINT FK_48E9CDABB1E1C39 FOREIGN KEY (association_type_id) REFERENCES sylius_product_association_type (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE shop_product_association_type');
        $this->addSql('ALTER TABLE shop_product_association DROP FOREIGN KEY FK_CFAD905B4584665A');
        $this->addSql('DROP INDEX IDX_CFAD905BB1E1C39 ON shop_product_association');
        $this->addSql('DROP INDEX IDX_CFAD905B4584665A ON shop_product_association');
        $this->addSql('DROP INDEX product_association_idx ON shop_product_association');
        $this->addSql('ALTER TABLE shop_product_association DROP association_type_id, DROP product_id');
        $this->addSql('ALTER TABLE sylius_product_association_product DROP FOREIGN KEY FK_A427B983EFB9C8A5');
        $this->addSql('ALTER TABLE sylius_product_association_product ADD CONSTRAINT FK_A427B983EFB9C8A5 FOREIGN KEY (association_id) REFERENCES sylius_product_association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_association_type_translation DROP FOREIGN KEY FK_4F618E52C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_association_type_translation ADD CONSTRAINT FK_4F618E52C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_association_type (id) ON DELETE CASCADE');
    }
}
