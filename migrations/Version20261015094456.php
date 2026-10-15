<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261015094456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX word_idx ON search_index (word)');
        $this->addSql('CREATE INDEX word_idx ON search_total (word)');
        $this->addSql('ALTER TABLE user_user ADD twoFactorMethod VARCHAR(255) DEFAULT NULL, ADD twoFactorRecoveryCode VARCHAR(255) DEFAULT NULL, ADD twoFactorSecretData JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX word_idx ON search_index');
        $this->addSql('ALTER TABLE user_user DROP twoFactorMethod, DROP twoFactorRecoveryCode, DROP twoFactorSecretData');
        $this->addSql('DROP INDEX word_idx ON search_total');
    }
}
