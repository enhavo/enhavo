<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200908091210 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE newsletter_subscriber_group DROP FOREIGN KEY FK_842BCA037808B1AD');
        $this->addSql('CREATE TABLE newsletter_local_subscriber (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter_local_subscriber_group (subscriber_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_1A8103D17808B1AD (subscriber_id), INDEX IDX_1A8103D1FE54D947 (group_id), PRIMARY KEY(subscriber_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE newsletter_local_subscriber_group ADD CONSTRAINT FK_1A8103D17808B1AD FOREIGN KEY (subscriber_id) REFERENCES newsletter_local_subscriber (id)');
        $this->addSql('ALTER TABLE newsletter_local_subscriber_group ADD CONSTRAINT FK_1A8103D1FE54D947 FOREIGN KEY (group_id) REFERENCES newsletter_group (id)');
        $this->addSql('DROP TABLE newsletter_pending_subscriber_group');
        $this->addSql('DROP TABLE newsletter_subscriber');
        $this->addSql('DROP TABLE newsletter_subscriber_group');
        $this->addSql('ALTER TABLE newsletter_pending_subscriber CHANGE data data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE newsletter_local_subscriber_group DROP FOREIGN KEY FK_1A8103D17808B1AD');
        $this->addSql('CREATE TABLE newsletter_pending_subscriber_group (subscriber_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_1C27815D7808B1AD (subscriber_id), INDEX IDX_1C27815DFE54D947 (group_id), PRIMARY KEY(subscriber_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE newsletter_subscriber (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, active TINYINT(1) DEFAULT NULL, activatedAt DATETIME DEFAULT NULL, createdAt DATETIME DEFAULT NULL, token VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE newsletter_subscriber_group (subscriber_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_842BCA037808B1AD (subscriber_id), INDEX IDX_842BCA03FE54D947 (group_id), PRIMARY KEY(subscriber_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE newsletter_pending_subscriber_group ADD CONSTRAINT FK_1C27815D7808B1AD FOREIGN KEY (subscriber_id) REFERENCES newsletter_pending_subscriber (id)');
        $this->addSql('ALTER TABLE newsletter_pending_subscriber_group ADD CONSTRAINT FK_1C27815DFE54D947 FOREIGN KEY (group_id) REFERENCES newsletter_group (id)');
        $this->addSql('ALTER TABLE newsletter_subscriber_group ADD CONSTRAINT FK_842BCA037808B1AD FOREIGN KEY (subscriber_id) REFERENCES newsletter_subscriber (id)');
        $this->addSql('ALTER TABLE newsletter_subscriber_group ADD CONSTRAINT FK_842BCA03FE54D947 FOREIGN KEY (group_id) REFERENCES newsletter_group (id)');
        $this->addSql('DROP TABLE newsletter_local_subscriber');
        $this->addSql('DROP TABLE newsletter_local_subscriber_group');
        $this->addSql('ALTER TABLE newsletter_pending_subscriber CHANGE data data LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
