<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191214133156 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE newsletter_receiver DROP FOREIGN KEY FK_E0BA4AFC7808B1AD');
        $this->addSql('DROP INDEX IDX_E0BA4AFC7808B1AD ON newsletter_receiver');
        $this->addSql('ALTER TABLE newsletter_receiver DROP subscriber_id');
        $this->addSql('ALTER TABLE newsletter_newsletter ADD state VARCHAR(255) DEFAULT NULL, ADD finishAt DATETIME DEFAULT NULL, DROP sent, CHANGE sentat startAt DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE newsletter_newsletter ADD sent TINYINT(1) DEFAULT NULL, ADD sentAt DATETIME DEFAULT NULL, DROP state, DROP startAt, DROP finishAt');
        $this->addSql('ALTER TABLE newsletter_receiver ADD subscriber_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter_receiver ADD CONSTRAINT FK_E0BA4AFC7808B1AD FOREIGN KEY (subscriber_id) REFERENCES newsletter_subscriber (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_E0BA4AFC7808B1AD ON newsletter_receiver (subscriber_id)');
    }
}
