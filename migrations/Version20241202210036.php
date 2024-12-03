<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202210036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person DROP FOREIGN KEY FK_D1DFBCF622C8FC20');
        $this->addSql('ALTER TABLE app_person ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_person ADD CONSTRAINT FK_D1DFBCF612469DE2 FOREIGN KEY (category_id) REFERENCES taxonomy_term (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_person ADD CONSTRAINT FK_D1DFBCF622C8FC20 FOREIGN KEY (occupation_id) REFERENCES taxonomy_term (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D1DFBCF612469DE2 ON app_person (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person DROP FOREIGN KEY FK_D1DFBCF612469DE2');
        $this->addSql('ALTER TABLE app_person DROP FOREIGN KEY FK_D1DFBCF622C8FC20');
        $this->addSql('DROP INDEX IDX_D1DFBCF612469DE2 ON app_person');
        $this->addSql('ALTER TABLE app_person DROP category_id');
        $this->addSql('ALTER TABLE app_person ADD CONSTRAINT FK_D1DFBCF622C8FC20 FOREIGN KEY (occupation_id) REFERENCES taxonomy_term (id)');
    }
}
