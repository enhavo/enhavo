<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241101180133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE revision_archive ADD resourceAlias VARCHAR(255) DEFAULT NULL, ADD title LONGTEXT DEFAULT NULL, ADD date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE revision_bin ADD resourceAlias VARCHAR(255) DEFAULT NULL, ADD title LONGTEXT DEFAULT NULL, ADD date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE revision_archive DROP resourceAlias, DROP title, DROP date');
        $this->addSql('ALTER TABLE revision_bin DROP resourceAlias, DROP title, DROP date');
    }
}
