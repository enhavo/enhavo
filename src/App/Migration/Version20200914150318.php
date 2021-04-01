<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200914150318 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE navigation_submenu DROP FOREIGN KEY FK_E232466B460D9FD7');
        $this->addSql('ALTER TABLE navigation_submenu ADD CONSTRAINT FK_E232466B460D9FD7 FOREIGN KEY (node_id) REFERENCES navigation_node (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE navigation_submenu DROP FOREIGN KEY FK_E232466B460D9FD7');
        $this->addSql('ALTER TABLE navigation_submenu ADD CONSTRAINT FK_E232466B460D9FD7 FOREIGN KEY (node_id) REFERENCES navigation_node (id)');
    }
}
