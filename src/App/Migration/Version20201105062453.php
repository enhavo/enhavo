<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201105062453 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_F7129A80A0D96FBF ON user_user');
        $this->addSql('DROP INDEX UNIQ_F7129A80C05FB297 ON user_user');
        $this->addSql('DROP INDEX UNIQ_F7129A8092FC23A8 ON user_user');
        $this->addSql('ALTER TABLE user_user ADD confirmationToken VARCHAR(255) DEFAULT NULL, ADD passwordRequestedAt DATETIME DEFAULT NULL, ADD lastLogin DATETIME DEFAULT NULL, DROP username_canonical, DROP email_canonical, DROP last_login, DROP confirmation_token, DROP password_requested_at, CHANGE username username VARCHAR(180) DEFAULT NULL, CHANGE email email VARCHAR(180) DEFAULT NULL, CHANGE enabled enabled TINYINT(1) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE roles roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7129A80E7927C74 ON user_user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7129A80F85E0677 ON user_user (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7129A801424F762 ON user_user (confirmationToken)');
        $this->addSql('DROP INDEX UNIQ_8F02BF9D5E237E06 ON user_group');
        $this->addSql('ALTER TABLE user_group CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE roles roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_group CHANGE name name VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE roles roles LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F02BF9D5E237E06 ON user_group (name)');
        $this->addSql('DROP INDEX UNIQ_F7129A80E7927C74 ON user_user');
        $this->addSql('DROP INDEX UNIQ_F7129A80F85E0677 ON user_user');
        $this->addSql('DROP INDEX UNIQ_F7129A801424F762 ON user_user');
        $this->addSql('ALTER TABLE user_user ADD username_canonical VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, ADD email_canonical VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, ADD last_login DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(180) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD password_requested_at DATETIME DEFAULT NULL, DROP confirmationToken, DROP passwordRequestedAt, DROP lastLogin, CHANGE email email VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE username username VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE password password VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE enabled enabled TINYINT(1) NOT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7129A80A0D96FBF ON user_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7129A80C05FB297 ON user_user (confirmation_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F7129A8092FC23A8 ON user_user (username_canonical)');
    }
}
