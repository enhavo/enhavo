<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191203113814 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE navigation_node DROP FOREIGN KEY FK_B2FF3D37727ACA70');
        $this->addSql('ALTER TABLE navigation_node ADD CONSTRAINT FK_B2FF3D37727ACA70 FOREIGN KEY (parent_id) REFERENCES navigation_node (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE navigation_node DROP FOREIGN KEY FK_B2FF3D37727ACA70');
        $this->addSql('ALTER TABLE navigation_node ADD CONSTRAINT FK_B2FF3D37727ACA70 FOREIGN KEY (parent_id) REFERENCES navigation_node (id) ON DELETE SET NULL');
    }
}
