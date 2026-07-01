<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241203204311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person ADD otherCategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_person ADD CONSTRAINT FK_D1DFBCF64C445AC7 FOREIGN KEY (otherCategory_id) REFERENCES taxonomy_term (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D1DFBCF64C445AC7 ON app_person (otherCategory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person DROP FOREIGN KEY FK_D1DFBCF64C445AC7');
        $this->addSql('DROP INDEX IDX_D1DFBCF64C445AC7 ON app_person');
        $this->addSql('ALTER TABLE app_person DROP otherCategory_id');
    }
}
