<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211101221431 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_person ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_person ADD CONSTRAINT FK_D1DFBCF6EE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id)');
        $this->addSql('CREATE INDEX IDX_D1DFBCF6EE45BDBF ON app_person (picture_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_person DROP FOREIGN KEY FK_D1DFBCF6EE45BDBF');
        $this->addSql('DROP INDEX IDX_D1DFBCF6EE45BDBF ON app_person');
        $this->addSql('ALTER TABLE app_person DROP picture_id');
    }
}
