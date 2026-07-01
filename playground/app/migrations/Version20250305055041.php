<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305055041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_test_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, direction VARCHAR(255) DEFAULT NULL, leftText VARCHAR(255) DEFAULT NULL, rightText VARCHAR(255) DEFAULT NULL, INDEX IDX_DEDE9A55460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_test_block ADD CONSTRAINT FK_DEDE9A55460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_test_block DROP FOREIGN KEY FK_DEDE9A55460D9FD7');
        $this->addSql('DROP TABLE app_test_block');
    }
}
