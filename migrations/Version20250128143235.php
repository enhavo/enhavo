<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128143235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE block_node ADD uuid VARCHAR(36) DEFAULT NULL');
        $this->addSql('UPDATE block_node SET uuid = uuid()');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4311BA3ED17F50A6 ON block_node (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_4311BA3ED17F50A6 ON block_node');
        $this->addSql('ALTER TABLE block_node DROP uuid');
    }
}
