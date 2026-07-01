<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127141736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_table (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_table_cell (id INT AUTO_INCREMENT NOT NULL, row_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, value VARCHAR(255) DEFAULT NULL, position INT NOT NULL, INDEX IDX_D74A6C5583A269F2 (row_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_table_row (id INT AUTO_INCREMENT NOT NULL, table_id INT DEFAULT NULL, position INT NOT NULL, INDEX IDX_25BA238AECFF285C (table_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_library_item_tag (item_id INT NOT NULL, term_id INT NOT NULL, INDEX IDX_8896E590126F525E (item_id), INDEX IDX_8896E590E2C35FC (term_id), PRIMARY KEY(item_id, term_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_table_cell ADD CONSTRAINT FK_D74A6C5583A269F2 FOREIGN KEY (row_id) REFERENCES app_table_row (id)');
        $this->addSql('ALTER TABLE app_table_row ADD CONSTRAINT FK_25BA238AECFF285C FOREIGN KEY (table_id) REFERENCES app_table (id)');
        $this->addSql('ALTER TABLE media_library_item_tag ADD CONSTRAINT FK_8896E590126F525E FOREIGN KEY (item_id) REFERENCES media_library_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_library_item_tag ADD CONSTRAINT FK_8896E590E2C35FC FOREIGN KEY (term_id) REFERENCES taxonomy_term (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_library_file_tag DROP FOREIGN KEY FK_40C0103BE2C35FC');
        $this->addSql('ALTER TABLE media_library_file_tag DROP FOREIGN KEY FK_40C0103B93CB796C');
        $this->addSql('DROP TABLE media_library_file_tag');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media_library_file_tag (file_id INT NOT NULL, term_id INT NOT NULL, INDEX IDX_40C0103B93CB796C (file_id), INDEX IDX_40C0103BE2C35FC (term_id), PRIMARY KEY(file_id, term_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE media_library_file_tag ADD CONSTRAINT FK_40C0103BE2C35FC FOREIGN KEY (term_id) REFERENCES taxonomy_term (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_library_file_tag ADD CONSTRAINT FK_40C0103B93CB796C FOREIGN KEY (file_id) REFERENCES media_library_item (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_table_cell DROP FOREIGN KEY FK_D74A6C5583A269F2');
        $this->addSql('ALTER TABLE app_table_row DROP FOREIGN KEY FK_25BA238AECFF285C');
        $this->addSql('ALTER TABLE media_library_item_tag DROP FOREIGN KEY FK_8896E590126F525E');
        $this->addSql('ALTER TABLE media_library_item_tag DROP FOREIGN KEY FK_8896E590E2C35FC');
        $this->addSql('DROP TABLE app_table');
        $this->addSql('DROP TABLE app_table_cell');
        $this->addSql('DROP TABLE app_table_row');
        $this->addSql('DROP TABLE media_library_item_tag');
    }
}
