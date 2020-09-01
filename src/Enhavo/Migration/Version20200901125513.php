<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200901125513 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_attribute_translation DROP FOREIGN KEY FK_93850EBA2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP FOREIGN KEY FK_8A053E54B6E62EFA');
        $this->addSql('ALTER TABLE sylius_product_option_translation DROP FOREIGN KEY FK_CBA491AD2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_option_value DROP FOREIGN KEY FK_F7FF7D4BA7C41D6F');
        $this->addSql('ALTER TABLE sylius_product_options DROP FOREIGN KEY FK_2B5FF009A7C41D6F');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value DROP FOREIGN KEY FK_76CDAFA13B69A9AF');
        $this->addSql('ALTER TABLE sylius_product_variant_translation DROP FOREIGN KEY FK_8DC18EDC2C2AC5D3');
        $this->addSql('CREATE TABLE shop_product_attribute (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, storage_type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT NOT NULL, name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_289D876B77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_product_variant (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT NOT NULL, UNIQUE INDEX UNIQ_C969A02977153098 (code), INDEX IDX_C969A0294584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A0294584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE sylius_product_attribute');
        $this->addSql('DROP TABLE sylius_product_option');
        $this->addSql('DROP TABLE sylius_product_variant');

        $this->addSql('ALTER TABLE sylius_product_options ADD CONSTRAINT FK_2B5FF009A7C41D6F FOREIGN KEY (option_id) REFERENCES shop_product_option (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE sylius_product_variant_option_value ADD CONSTRAINT FK_76CDAFA13B69A9AF FOREIGN KEY (variant_id) REFERENCES shop_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_option DROP FOREIGN KEY FK_A85B401234ECB4E6');
        $this->addSql('DROP INDEX IDX_A85B401234ECB4E6 ON shop_product_option');
        $this->addSql('ALTER TABLE shop_product_option ADD name VARCHAR(255) DEFAULT NULL, DROP route_id');
        $this->addSql('ALTER TABLE sylius_product_variant_translation ADD CONSTRAINT FK_8DC18EDC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_option_value ADD CONSTRAINT FK_F7FF7D4BA7C41D6F FOREIGN KEY (option_id) REFERENCES shop_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_option_translation ADD CONSTRAINT FK_CBA491AD2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_attribute_value ADD CONSTRAINT FK_8A053E54B6E62EFA FOREIGN KEY (attribute_id) REFERENCES shop_product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_attribute_translation ADD CONSTRAINT FK_93850EBA2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_product_attribute (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP FOREIGN KEY FK_8A053E54B6E62EFA');
        $this->addSql('ALTER TABLE sylius_product_attribute_translation DROP FOREIGN KEY FK_93850EBA2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value DROP FOREIGN KEY FK_76CDAFA13B69A9AF');
        $this->addSql('ALTER TABLE sylius_product_variant_translation DROP FOREIGN KEY FK_8DC18EDC2C2AC5D3');
        $this->addSql('CREATE TABLE sylius_product_attribute (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, storage_type VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, configuration LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT NOT NULL, UNIQUE INDEX UNIQ_BFAF484A77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sylius_product_option (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_E4C0EBEF77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sylius_product_variant (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, code VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT NOT NULL, INDEX IDX_A29B5234584665A (product_id), UNIQUE INDEX UNIQ_A29B52377153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sylius_product_variant ADD CONSTRAINT FK_A29B5234584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE shop_product_attribute');
        $this->addSql('DROP TABLE shop_product_variant');
        $this->addSql('ALTER TABLE shop_product_option ADD route_id INT DEFAULT NULL, DROP name');
        $this->addSql('ALTER TABLE shop_product_option ADD CONSTRAINT FK_A85B401234ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id)');
        $this->addSql('CREATE INDEX IDX_A85B401234ECB4E6 ON shop_product_option (route_id)');
        $this->addSql('ALTER TABLE sylius_product_attribute_translation DROP FOREIGN KEY FK_93850EBA2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_attribute_translation ADD CONSTRAINT FK_93850EBA2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP FOREIGN KEY FK_8A053E54B6E62EFA');
        $this->addSql('ALTER TABLE sylius_product_attribute_value ADD CONSTRAINT FK_8A053E54B6E62EFA FOREIGN KEY (attribute_id) REFERENCES sylius_product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_option_translation DROP FOREIGN KEY FK_CBA491AD2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_option_translation ADD CONSTRAINT FK_CBA491AD2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_option_value DROP FOREIGN KEY FK_F7FF7D4BA7C41D6F');
        $this->addSql('ALTER TABLE sylius_product_option_value ADD CONSTRAINT FK_F7FF7D4BA7C41D6F FOREIGN KEY (option_id) REFERENCES sylius_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_options DROP FOREIGN KEY FK_2B5FF009A7C41D6F');
        $this->addSql('ALTER TABLE sylius_product_options ADD CONSTRAINT FK_2B5FF009A7C41D6F FOREIGN KEY (option_id) REFERENCES sylius_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value DROP FOREIGN KEY FK_76CDAFA13B69A9AF');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value ADD CONSTRAINT FK_76CDAFA13B69A9AF FOREIGN KEY (variant_id) REFERENCES sylius_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_variant_translation DROP FOREIGN KEY FK_8DC18EDC2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_variant_translation ADD CONSTRAINT FK_8DC18EDC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_variant (id) ON DELETE CASCADE');
    }
}
