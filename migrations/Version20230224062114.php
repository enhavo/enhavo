<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224062114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_file ADD garbageCheckedAt DATETIME DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_F7129A80F85E0677 ON user_user');
        $this->addSql('DROP INDEX UNIQ_F7129A80E7927C74 ON user_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_file DROP garbageCheckedAt');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7129A80F85E0677 ON user_user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7129A80E7927C74 ON user_user (email)');
    }
}
