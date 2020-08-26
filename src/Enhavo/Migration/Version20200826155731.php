<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826155731 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_options ADD CONSTRAINT FK_2B5FF009A7C41D6F FOREIGN KEY (option_id) REFERENCES shop_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value ADD CONSTRAINT FK_76CDAFA13B69A9AF FOREIGN KEY (variant_id) REFERENCES shop_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_variant_translation ADD CONSTRAINT FK_8DC18EDC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_option_value ADD CONSTRAINT FK_F7FF7D4BA7C41D6F FOREIGN KEY (option_id) REFERENCES shop_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_option_translation ADD CONSTRAINT FK_CBA491AD2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_product_option (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_option_translation DROP FOREIGN KEY FK_CBA491AD2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_option_value DROP FOREIGN KEY FK_F7FF7D4BA7C41D6F');
        $this->addSql('ALTER TABLE sylius_product_options DROP FOREIGN KEY FK_2B5FF009A7C41D6F');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value DROP FOREIGN KEY FK_76CDAFA13B69A9AF');
        $this->addSql('ALTER TABLE sylius_product_variant_translation DROP FOREIGN KEY FK_8DC18EDC2C2AC5D3');
    }
}
