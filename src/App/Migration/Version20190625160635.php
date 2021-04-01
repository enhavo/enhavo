<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625160635 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE enhavo_template_resource_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, INDEX IDX_7FD350EF460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enhavo_template_resource_block ADD CONSTRAINT FK_7FD350EF460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_template ADD route_id INT DEFAULT NULL, DROP name');
        $this->addSql('ALTER TABLE template_template ADD CONSTRAINT FK_C8362FF034ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8362FF034ECB4E6 ON template_template (route_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE enhavo_template_resource_block');
        $this->addSql('ALTER TABLE template_template DROP FOREIGN KEY FK_C8362FF034ECB4E6');
        $this->addSql('DROP INDEX UNIQ_C8362FF034ECB4E6 ON template_template');
        $this->addSql('ALTER TABLE template_template ADD name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP route_id');
    }
}
