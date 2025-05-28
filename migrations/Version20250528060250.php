<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528060250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE app_table (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE app_table_cell (id INT AUTO_INCREMENT NOT NULL, row_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, value VARCHAR(255) DEFAULT NULL, position INT NOT NULL, INDEX IDX_D74A6C5583A269F2 (row_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE app_table_row (id INT AUTO_INCREMENT NOT NULL, table_id INT DEFAULT NULL, position INT NOT NULL, INDEX IDX_25BA238AECFF285C (table_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sylius_promotion_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, `label` VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_3C7A76182C2AC5D3 (translatable_id), UNIQUE INDEX sylius_promotion_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sylius_tax_category (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_221EB0BE77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sylius_tax_rate (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, amount NUMERIC(10, 5) NOT NULL, included_in_price TINYINT(1) NOT NULL, calculator VARCHAR(255) NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3CD86B2E77153098 (code), INDEX IDX_3CD86B2E12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE app_table_cell ADD CONSTRAINT FK_D74A6C5583A269F2 FOREIGN KEY (row_id) REFERENCES app_table_row (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE app_table_row ADD CONSTRAINT FK_25BA238AECFF285C FOREIGN KEY (table_id) REFERENCES app_table (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_promotion_translation ADD CONSTRAINT FK_3C7A76182C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_promotion (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_tax_rate ADD CONSTRAINT FK_3CD86B2E12469DE2 FOREIGN KEY (category_id) REFERENCES sylius_tax_category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product DROP FOREIGN KEY FK_D07944872C061291
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product ADD CONSTRAINT FK_D07944872C061291 FOREIGN KEY (taxCategory_id) REFERENCES sylius_tax_category (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product_attribute CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A0292C061291
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A0292C061291 FOREIGN KEY (taxCategory_id) REFERENCES sylius_tax_category (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_promotion ADD archived_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_shipping_method DROP FOREIGN KEY FK_9CF0FB1B2C061291
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_shipping_method CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_shipping_method ADD CONSTRAINT FK_9CF0FB1B2C061291 FOREIGN KEY (taxCategory_id) REFERENCES sylius_tax_category (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_tax_rate DROP FOREIGN KEY FK_18DBCA5712469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_18DBCA5712469DE2 ON shop_tax_rate
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_tax_rate DROP category_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_address_log_entries CHANGE data data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX object_id_index ON sylius_address_log_entries (object_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX object_class_index ON sylius_address_log_entries (object_class)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_catalog_promotion_action CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_catalog_promotion_scope CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_promotion_action CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_promotion_rule CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_shipping_method_rule CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:json)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product DROP FOREIGN KEY FK_D07944872C061291
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A0292C061291
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_shipping_method DROP FOREIGN KEY FK_9CF0FB1B2C061291
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE app_table_cell DROP FOREIGN KEY FK_D74A6C5583A269F2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE app_table_row DROP FOREIGN KEY FK_25BA238AECFF285C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_promotion_translation DROP FOREIGN KEY FK_3C7A76182C2AC5D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_tax_rate DROP FOREIGN KEY FK_3CD86B2E12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE app_table
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE app_table_cell
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE app_table_row
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sylius_promotion_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sylius_tax_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sylius_tax_rate
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product DROP FOREIGN KEY FK_D07944872C061291
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product ADD CONSTRAINT FK_D07944872C061291 FOREIGN KEY (taxCategory_id) REFERENCES shop_tax_category (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product_attribute CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A0292C061291
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A0292C061291 FOREIGN KEY (taxCategory_id) REFERENCES shop_tax_category (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_promotion DROP archived_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_shipping_method DROP FOREIGN KEY FK_9CF0FB1B2C061291
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_shipping_method CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_shipping_method ADD CONSTRAINT FK_9CF0FB1B2C061291 FOREIGN KEY (taxCategory_id) REFERENCES shop_tax_category (id) ON DELETE SET NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_tax_rate ADD category_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE shop_tax_rate ADD CONSTRAINT FK_18DBCA5712469DE2 FOREIGN KEY (category_id) REFERENCES shop_tax_category (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_18DBCA5712469DE2 ON shop_tax_rate (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX object_id_index ON sylius_address_log_entries
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX object_class_index ON sylius_address_log_entries
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_address_log_entries CHANGE data data LONGTEXT NOT NULL COMMENT '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_catalog_promotion_action CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_catalog_promotion_scope CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_promotion_action CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_promotion_rule CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sylius_shipping_method_rule CHANGE configuration configuration LONGTEXT NOT NULL COMMENT '(DC2Type:array)'
        SQL);
    }
}
