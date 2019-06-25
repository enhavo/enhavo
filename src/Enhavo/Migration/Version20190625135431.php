<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625135431 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE block_template_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, INDEX IDX_886C4B4A460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE block_template_block ADD CONSTRAINT FK_886C4B4A460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_node ADD template VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE block_template_block');
        $this->addSql('ALTER TABLE block_node DROP template');
    }
}
