<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190827211923 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE newsletter_receiver (id INT AUTO_INCREMENT NOT NULL, subscriber_id INT DEFAULT NULL, newsletter_id INT DEFAULT NULL, eMail VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, sentAt DATETIME DEFAULT NULL, parameters LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', token VARCHAR(255) NOT NULL, INDEX IDX_E0BA4AFC7808B1AD (subscriber_id), INDEX IDX_E0BA4AFC22DB1917 (newsletter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_tracking (id INT AUTO_INCREMENT NOT NULL, receiver_id INT DEFAULT NULL, date DATETIME DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_757EA476CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE newsletter_receiver ADD CONSTRAINT FK_E0BA4AFC7808B1AD FOREIGN KEY (subscriber_id) REFERENCES newsletter_subscriber (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_receiver ADD CONSTRAINT FK_E0BA4AFC22DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter_newsletter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_tracking ADD CONSTRAINT FK_757EA476CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES newsletter_receiver (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE translation_translation_string');
        $this->addSql('ALTER TABLE newsletter_newsletter ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter_newsletter ADD CONSTRAINT FK_9390BC62FE54D947 FOREIGN KEY (group_id) REFERENCES newsletter_group (id)');
        $this->addSql('CREATE INDEX IDX_9390BC62FE54D947 ON newsletter_newsletter (group_id)');
        $this->addSql('CREATE INDEX IDX_323FC9CAA393D2FB43625D9F ON shop_order (state, updated_at)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE newsletter_tracking DROP FOREIGN KEY FK_757EA476CD53EDB6');
        $this->addSql('CREATE TABLE translation_translation_string (id INT AUTO_INCREMENT NOT NULL, translationKey VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, translationValue LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE newsletter_receiver');
        $this->addSql('DROP TABLE newsletter_tracking');
        $this->addSql('ALTER TABLE newsletter_newsletter DROP FOREIGN KEY FK_9390BC62FE54D947');
        $this->addSql('DROP INDEX IDX_9390BC62FE54D947 ON newsletter_newsletter');
        $this->addSql('ALTER TABLE newsletter_newsletter DROP group_id');
        $this->addSql('DROP INDEX IDX_323FC9CAA393D2FB43625D9F ON shop_order');
    }
}
