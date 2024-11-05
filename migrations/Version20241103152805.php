<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241103152805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media_library_item (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, contentType VARCHAR(50) DEFAULT NULL, INDEX IDX_D02545FF93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media_library_item ADD CONSTRAINT FK_D02545FF93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE media_file ADD item_id INT DEFAULT NULL, DROP library, DROP contentType');
        $this->addSql('ALTER TABLE media_file ADD CONSTRAINT FK_4FD8E9C3126F525E FOREIGN KEY (item_id) REFERENCES media_library_item (id)');
        $this->addSql('CREATE INDEX IDX_4FD8E9C3126F525E ON media_file (item_id)');
        $this->addSql('ALTER TABLE media_library_file_tag DROP FOREIGN KEY FK_40C0103B93CB796C');
        $this->addSql('ALTER TABLE media_library_file_tag ADD CONSTRAINT FK_40C0103B93CB796C FOREIGN KEY (file_id) REFERENCES media_library_item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_file DROP FOREIGN KEY FK_4FD8E9C3126F525E');
        $this->addSql('ALTER TABLE media_library_file_tag DROP FOREIGN KEY FK_40C0103B93CB796C');
        $this->addSql('ALTER TABLE media_library_item DROP FOREIGN KEY FK_D02545FF93CB796C');
        $this->addSql('DROP TABLE media_library_item');
        $this->addSql('DROP INDEX IDX_4FD8E9C3126F525E ON media_file');
        $this->addSql('ALTER TABLE media_file ADD library TINYINT(1) DEFAULT NULL, ADD contentType VARCHAR(50) DEFAULT NULL, DROP item_id');
        $this->addSql('ALTER TABLE media_library_file_tag DROP FOREIGN KEY FK_40C0103B93CB796C');
        $this->addSql('ALTER TABLE media_library_file_tag ADD CONSTRAINT FK_40C0103B93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE CASCADE');
    }
}
