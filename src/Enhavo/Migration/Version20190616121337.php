<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190616121337 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE redirect_redirect (id INT AUTO_INCREMENT NOT NULL, route_id INT DEFAULT NULL, `from` VARCHAR(512) DEFAULT NULL, `to` VARCHAR(512) DEFAULT NULL, code INT DEFAULT NULL, INDEX IDX_87BE679834ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE redirect_redirect ADD CONSTRAINT FK_87BE679834ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE content_redirect');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE content_redirect (id INT AUTO_INCREMENT NOT NULL, route_id INT DEFAULT NULL, `from` VARCHAR(512) DEFAULT NULL COLLATE utf8_unicode_ci, `to` VARCHAR(512) DEFAULT NULL COLLATE utf8_unicode_ci, code INT DEFAULT NULL, INDEX IDX_91BB74C434ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE content_redirect ADD CONSTRAINT FK_91BB74C434ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE redirect_redirect');
    }
}
