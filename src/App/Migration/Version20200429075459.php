<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200429075459 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE newsletter_newsletter_attachment (newsletter_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_EDFC9CA122DB1917 (newsletter_id), INDEX IDX_EDFC9CA193CB796C (file_id), PRIMARY KEY(newsletter_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE newsletter_newsletter_attachment ADD CONSTRAINT FK_EDFC9CA122DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter_newsletter (id)');
        $this->addSql('ALTER TABLE newsletter_newsletter_attachment ADD CONSTRAINT FK_EDFC9CA193CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE media_format ADD lockAt DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE newsletter_newsletter_attachment');
        $this->addSql('ALTER TABLE media_format DROP lockAt');
    }
}
