<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210124113417 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE setting_basic_value (id INT AUTO_INCREMENT NOT NULL, setting_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, `int` INT DEFAULT NULL, `float` DOUBLE PRECISION DEFAULT NULL, `varchar` VARCHAR(255) DEFAULT NULL, `boolean` TINYINT(1) DEFAULT NULL, INDEX IDX_FCEB877FEE35BD72 (setting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_text_value (id INT AUTO_INCREMENT NOT NULL, setting_id INT DEFAULT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_8D92FD49EE35BD72 (setting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_date_value (id INT AUTO_INCREMENT NOT NULL, setting_id INT DEFAULT NULL, value DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_61D6AF98EE35BD72 (setting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_media_value (id INT AUTO_INCREMENT NOT NULL, setting_id INT DEFAULT NULL, file_id INT DEFAULT NULL, multiple TINYINT(1) NOT NULL, INDEX IDX_39C6ED52EE35BD72 (setting_id), UNIQUE INDEX UNIQ_39C6ED5293CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_media_value_files (file_value_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_659369924870343F (file_value_id), INDEX IDX_6593699293CB796C (file_id), PRIMARY KEY(file_value_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE setting_basic_value ADD CONSTRAINT FK_FCEB877FEE35BD72 FOREIGN KEY (setting_id) REFERENCES setting_setting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_text_value ADD CONSTRAINT FK_8D92FD49EE35BD72 FOREIGN KEY (setting_id) REFERENCES setting_setting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_date_value ADD CONSTRAINT FK_61D6AF98EE35BD72 FOREIGN KEY (setting_id) REFERENCES setting_setting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_media_value ADD CONSTRAINT FK_39C6ED52EE35BD72 FOREIGN KEY (setting_id) REFERENCES setting_setting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_media_value ADD CONSTRAINT FK_39C6ED5293CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE setting_media_value_files ADD CONSTRAINT FK_659369924870343F FOREIGN KEY (file_value_id) REFERENCES setting_media_value (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_media_value_files ADD CONSTRAINT FK_6593699293CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE setting_setting_files');
        $this->addSql('ALTER TABLE setting_setting DROP FOREIGN KEY FK_C726087093CB796C');
        $this->addSql('DROP INDEX UNIQ_C726087093CB796C ON setting_setting');
        $this->addSql('ALTER TABLE setting_setting ADD valueClass VARCHAR(255) DEFAULT NULL, ADD position INT DEFAULT NULL, ADD `group` VARCHAR(255) DEFAULT NULL, DROP type, DROP value, DROP date, CHANGE file_id valueId INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE setting_media_value_files DROP FOREIGN KEY FK_659369924870343F');
        $this->addSql('CREATE TABLE setting_setting_files (setting_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_A6E69AE2EE35BD72 (setting_id), INDEX IDX_A6E69AE293CB796C (file_id), PRIMARY KEY(setting_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE setting_setting_files ADD CONSTRAINT FK_A6E69AE293CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_setting_files ADD CONSTRAINT FK_A6E69AE2EE35BD72 FOREIGN KEY (setting_id) REFERENCES setting_setting (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE setting_basic_value');
        $this->addSql('DROP TABLE setting_text_value');
        $this->addSql('DROP TABLE setting_date_value');
        $this->addSql('DROP TABLE setting_media_value');
        $this->addSql('DROP TABLE setting_media_value_files');
        $this->addSql('ALTER TABLE setting_setting ADD file_id INT DEFAULT NULL, ADD type LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD value LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, ADD date DATETIME DEFAULT NULL, DROP valueId, DROP valueClass, DROP position, DROP `group`');
        $this->addSql('ALTER TABLE setting_setting ADD CONSTRAINT FK_C726087093CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C726087093CB796C ON setting_setting (file_id)');
    }
}
