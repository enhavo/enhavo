<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200824075733 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE translation_translation_file (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, class VARCHAR(255) DEFAULT NULL, property VARCHAR(255) DEFAULT NULL, refId INT DEFAULT NULL, locale VARCHAR(255) DEFAULT NULL, INDEX IDX_EC79D9C993CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE translation_translation_file ADD CONSTRAINT FK_EC79D9C993CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE translation_translation_file');
    }
}
