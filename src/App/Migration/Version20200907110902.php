<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200907110902 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE newsletter_pending_subscriber (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_pending_subscriber_group (subscriber_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_1C27815D7808B1AD (subscriber_id), INDEX IDX_1C27815DFE54D947 (group_id), PRIMARY KEY(subscriber_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE newsletter_pending_subscriber_group ADD CONSTRAINT FK_1C27815D7808B1AD FOREIGN KEY (subscriber_id) REFERENCES newsletter_pending_subscriber (id)');
        $this->addSql('ALTER TABLE newsletter_pending_subscriber_group ADD CONSTRAINT FK_1C27815DFE54D947 FOREIGN KEY (group_id) REFERENCES newsletter_group (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE newsletter_pending_subscriber_group DROP FOREIGN KEY FK_1C27815D7808B1AD');
        $this->addSql('DROP TABLE newsletter_pending_subscriber');
        $this->addSql('DROP TABLE newsletter_pending_subscriber_group');
    }
}
