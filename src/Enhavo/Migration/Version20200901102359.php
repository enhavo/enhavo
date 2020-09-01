<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200901102359 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_attribute_value ADD CONSTRAINT FK_8A053E54B6E62EFA FOREIGN KEY (attribute_id) REFERENCES shop_product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_attribute_translation ADD CONSTRAINT FK_93850EBA2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES shop_product_attribute (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_attribute_translation DROP FOREIGN KEY FK_93850EBA2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP FOREIGN KEY FK_8A053E54B6E62EFA');
    }
}
