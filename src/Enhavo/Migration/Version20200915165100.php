<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915165100 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_tax_rate DROP FOREIGN KEY FK_3CD86B2E12469DE2');
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D0794487C6CD2B2B');
        $this->addSql('DROP TABLE sylius_tax_category');
        $this->addSql('DROP TABLE sylius_tax_rate');
        $this->addSql('ALTER TABLE shop_tax_rate ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE shop_tax_rate ADD CONSTRAINT FK_18DBCA5712469DE2 FOREIGN KEY (category_id) REFERENCES shop_tax_category (id)');
        $this->addSql('CREATE INDEX IDX_18DBCA5712469DE2 ON shop_tax_rate (category_id)');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D0794487C6CD2B2B FOREIGN KEY (taxRate_id) REFERENCES shop_tax_rate (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_tax_category (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, description LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_221EB0BE77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sylius_tax_rate (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, code VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, amount NUMERIC(10, 5) NOT NULL, included_in_price TINYINT(1) NOT NULL, calculator VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_3CD86B2E12469DE2 (category_id), UNIQUE INDEX UNIQ_3CD86B2E77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sylius_tax_rate ADD CONSTRAINT FK_3CD86B2E12469DE2 FOREIGN KEY (category_id) REFERENCES sylius_tax_category (id)');
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D0794487C6CD2B2B');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D0794487C6CD2B2B FOREIGN KEY (taxRate_id) REFERENCES sylius_tax_rate (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shop_tax_rate DROP FOREIGN KEY FK_18DBCA5712469DE2');
        $this->addSql('DROP INDEX IDX_18DBCA5712469DE2 ON shop_tax_rate');
        $this->addSql('ALTER TABLE shop_tax_rate DROP category_id');
    }
}
