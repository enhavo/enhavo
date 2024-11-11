<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030152505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE app_person ADD revisionDate DATETIME DEFAULT NULL, ADD revisionState VARCHAR(255) DEFAULT NULL, ADD revisionParameters LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD revisionSubject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_person ADD CONSTRAINT FK_D1DFBCF67DB2BC3B FOREIGN KEY (revisionSubject_id) REFERENCES app_person (id)');
        $this->addSql('CREATE INDEX IDX_D1DFBCF67DB2BC3B ON app_person (revisionSubject_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person DROP FOREIGN KEY FK_D1DFBCF67DB2BC3B');
        $this->addSql('DROP INDEX IDX_D1DFBCF67DB2BC3B ON app_person');
        $this->addSql('ALTER TABLE app_person DROP revisionDate, DROP revisionState, DROP revisionParameters, DROP revisionSubject_id');
    }
}
