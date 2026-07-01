<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619042720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_article ADD canonicalUrl VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar_appointment ADD canonicalUrl VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page_page ADD canonicalUrl VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_article DROP canonicalUrl');
        $this->addSql('ALTER TABLE calendar_appointment DROP canonicalUrl');
        $this->addSql('ALTER TABLE page_page DROP canonicalUrl');
    }
}
