<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221127111250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_user ADD userIdentifier VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE username username VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE user_user SET userIdentifier = email');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7129A80750FAC43 ON user_user (userIdentifier)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_F7129A80750FAC43 ON user_user');
        $this->addSql('ALTER TABLE user_user DROP userIdentifier, CHANGE email email VARCHAR(180) DEFAULT NULL, CHANGE username username VARCHAR(180) DEFAULT NULL');
    }
}
