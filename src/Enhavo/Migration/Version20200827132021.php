<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200827132021 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shop_product_option DROP FOREIGN KEY FK_A85B401234ECB4E6');
        $this->addSql('DROP INDEX IDX_A85B401234ECB4E6 ON shop_product_option');
        $this->addSql('ALTER TABLE shop_product_option DROP route_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shop_product_option ADD route_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop_product_option ADD CONSTRAINT FK_A85B401234ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id)');
        $this->addSql('CREATE INDEX IDX_A85B401234ECB4E6 ON shop_product_option (route_id)');
    }
}
