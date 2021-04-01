<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190701215231 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE enhavo_slider_slider_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, INDEX IDX_19197EFB460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enhavo_newsletter_subscribe_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, INDEX IDX_3AE60C5C460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enhavo_calendar_calendar_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, INDEX IDX_2FAF88F9460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enhavo_contact_contact_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, INDEX IDX_1B10C4F2460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_blockquote_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, author VARCHAR(255) DEFAULT NULL, INDEX IDX_F1BBEE4E460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enhavo_slider_slider_block ADD CONSTRAINT FK_19197EFB460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enhavo_newsletter_subscribe_block ADD CONSTRAINT FK_3AE60C5C460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enhavo_calendar_calendar_block ADD CONSTRAINT FK_2FAF88F9460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enhavo_contact_contact_block ADD CONSTRAINT FK_1B10C4F2460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_blockquote_block ADD CONSTRAINT FK_F1BBEE4E460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE block_cite_block');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE block_cite_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_D02CDA4460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE block_cite_block ADD CONSTRAINT FK_D02CDA4460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE enhavo_slider_slider_block');
        $this->addSql('DROP TABLE enhavo_newsletter_subscribe_block');
        $this->addSql('DROP TABLE enhavo_calendar_calendar_block');
        $this->addSql('DROP TABLE enhavo_contact_contact_block');
        $this->addSql('DROP TABLE block_blockquote_block');
    }
}
