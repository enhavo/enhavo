<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200702033906 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE navigation_content (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, INDEX IDX_B8473EDA460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE navigation_submenu (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, INDEX IDX_E232466B460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE navigation_content ADD CONSTRAINT FK_B8473EDA460D9FD7 FOREIGN KEY (node_id) REFERENCES navigation_node (id)');
        $this->addSql('ALTER TABLE navigation_submenu ADD CONSTRAINT FK_E232466B460D9FD7 FOREIGN KEY (node_id) REFERENCES navigation_node (id)');
        $this->addSql('ALTER TABLE navigation_node CHANGE navitemid subjectId INT DEFAULT NULL, CHANGE navitemclass subjectClass VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE navigation_link ADD node_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE navigation_link ADD CONSTRAINT FK_12C4C83460D9FD7 FOREIGN KEY (node_id) REFERENCES navigation_node (id)');
        $this->addSql('CREATE INDEX IDX_12C4C83460D9FD7 ON navigation_link (node_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE navigation_content');
        $this->addSql('DROP TABLE navigation_submenu');
        $this->addSql('ALTER TABLE navigation_link DROP FOREIGN KEY FK_12C4C83460D9FD7');
        $this->addSql('DROP INDEX IDX_12C4C83460D9FD7 ON navigation_link');
        $this->addSql('ALTER TABLE navigation_link DROP node_id');
        $this->addSql('ALTER TABLE navigation_node CHANGE subjectid navItemId INT DEFAULT NULL, CHANGE subjectclass navItemClass VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
    }
}
