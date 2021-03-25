<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325142911 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_option_value_translation DROP FOREIGN KEY FK_8D4382DC2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value DROP FOREIGN KEY FK_76CDAFA1D957CA06');
        $this->addSql('CREATE TABLE shop_product_option_value (id INT AUTO_INCREMENT NOT NULL, option_id INT NOT NULL, code VARCHAR(255) NOT NULL, position INT DEFAULT NULL, UNIQUE INDEX UNIQ_4AC5CB0A77153098 (code), INDEX IDX_4AC5CB0AA7C41D6F (option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE sylius_product_option_value');
        $this->addSql('ALTER TABLE shop_product_option DROP name');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_product_option_value (id INT AUTO_INCREMENT NOT NULL, option_id INT NOT NULL, code VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, INDEX IDX_F7FF7D4BA7C41D6F (option_id), UNIQUE INDEX UNIQ_F7FF7D4B77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sylius_product_option_value ADD CONSTRAINT FK_F7FF7D4BA7C41D6F FOREIGN KEY (option_id) REFERENCES shop_product_option (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE shop_product_option_value');
        $this->addSql('ALTER TABLE shop_product_option ADD name VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE sylius_product_option_value_translation ADD CONSTRAINT FK_8D4382DC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_option_value (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value ADD CONSTRAINT FK_76CDAFA1D957CA06 FOREIGN KEY (option_value_id) REFERENCES sylius_product_option_value (id) ON DELETE CASCADE');
    }
}
