<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200702023428 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE newsletter_group DROP discr');
        $this->addSql('ALTER TABLE navigation_node ADD name VARCHAR(255) DEFAULT NULL, ADD navItemClass VARCHAR(255) DEFAULT NULL, DROP type, DROP configuration, DROP contentClass, CHANGE contentid navItemId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE navigation_link ADD target VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE navigation_link DROP target');
        $this->addSql('ALTER TABLE navigation_node ADD type VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD configuration LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:json_array)\', ADD contentClass VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, DROP name, DROP navItemClass, CHANGE navitemid contentId INT DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter_group ADD discr VARCHAR(6) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
