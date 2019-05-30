<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190530200713 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sidebar_sidebar (id INT AUTO_INCREMENT NOT NULL, grid_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_E38335252CF16895 (grid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sidebar_item_sidebar_column (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, column_id INT DEFAULT NULL, sidebar_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, INDEX IDX_6C0652D8126F525E (item_id), INDEX IDX_6C0652D8BE8E8ED5 (column_id), INDEX IDX_6C0652D83A432888 (sidebar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_template (id INT AUTO_INCREMENT NOT NULL, grid_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C8362FF02CF16895 (grid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sidebar_sidebar ADD CONSTRAINT FK_E38335252CF16895 FOREIGN KEY (grid_id) REFERENCES grid_grid (id)');
        $this->addSql('ALTER TABLE sidebar_item_sidebar_column ADD CONSTRAINT FK_6C0652D8126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_item_sidebar_column ADD CONSTRAINT FK_6C0652D8BE8E8ED5 FOREIGN KEY (column_id) REFERENCES grid_grid (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_item_sidebar_column ADD CONSTRAINT FK_6C0652D83A432888 FOREIGN KEY (sidebar_id) REFERENCES sidebar_sidebar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_template ADD CONSTRAINT FK_C8362FF02CF16895 FOREIGN KEY (grid_id) REFERENCES grid_grid (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sidebar_item_sidebar_column DROP FOREIGN KEY FK_6C0652D83A432888');
        $this->addSql('DROP TABLE sidebar_sidebar');
        $this->addSql('DROP TABLE sidebar_item_sidebar_column');
        $this->addSql('DROP TABLE template_template');
    }
}
