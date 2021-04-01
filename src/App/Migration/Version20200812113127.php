<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200812113127 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translation_translation_route DROP FOREIGN KEY FK_FDABACE634ECB4E6');
        $this->addSql('ALTER TABLE translation_translation_route ADD CONSTRAINT FK_FDABACE634ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE translation_translation_route DROP FOREIGN KEY FK_FDABACE634ECB4E6');
        $this->addSql('ALTER TABLE translation_translation_route ADD CONSTRAINT FK_FDABACE634ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE CASCADE');
    }
}
