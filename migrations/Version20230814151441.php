<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230814151441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_article ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, DROP created, DROP updated');
        $this->addSql('ALTER TABLE calendar_appointment ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, DROP created, DROP updated');
        $this->addSql('ALTER TABLE page_page ADD createdAt DATETIME DEFAULT NULL, ADD updatedAt DATETIME DEFAULT NULL, DROP created, DROP updated');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_article ADD created DATETIME NOT NULL, ADD updated DATETIME NOT NULL, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE calendar_appointment ADD created DATETIME NOT NULL, ADD updated DATETIME NOT NULL, DROP createdAt, DROP updatedAt');
        $this->addSql('ALTER TABLE page_page ADD created DATETIME NOT NULL, ADD updated DATETIME NOT NULL, DROP createdAt, DROP updatedAt');
    }
}
