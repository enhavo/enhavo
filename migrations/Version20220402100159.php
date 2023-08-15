<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220402100159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media_library_file_tag (file_id INT NOT NULL, term_id INT NOT NULL, INDEX IDX_40C0103B93CB796C (file_id), INDEX IDX_40C0103BE2C35FC (term_id), PRIMARY KEY(file_id, term_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media_library_file_tag ADD CONSTRAINT FK_40C0103B93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_library_file_tag ADD CONSTRAINT FK_40C0103BE2C35FC FOREIGN KEY (term_id) REFERENCES taxonomy_term (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_file ADD discr VARCHAR(255) NOT NULL, ADD contentType VARCHAR(50) DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE media_library_file_tag');
        $this->addSql('ALTER TABLE media_file DROP discr, DROP contentType');
    }
}
