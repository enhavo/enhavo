<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211030114716 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA727ACA70');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA727ACA70 FOREIGN KEY (parent_id) REFERENCES page_page (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_person ADD occupation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_person ADD CONSTRAINT FK_D1DFBCF622C8FC20 FOREIGN KEY (occupation_id) REFERENCES taxonomy_term (id)');
        $this->addSql('CREATE INDEX IDX_D1DFBCF622C8FC20 ON app_person (occupation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_person DROP FOREIGN KEY FK_D1DFBCF622C8FC20');
        $this->addSql('DROP INDEX IDX_D1DFBCF622C8FC20 ON app_person');
        $this->addSql('ALTER TABLE app_person DROP occupation_id');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA727ACA70');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA727ACA70 FOREIGN KEY (parent_id) REFERENCES page_page (id)');
    }
}
