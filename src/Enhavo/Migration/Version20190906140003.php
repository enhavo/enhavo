<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190906140003 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment_thread (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_comment (id INT AUTO_INCREMENT NOT NULL, thread_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, createdAt LONGTEXT DEFAULT NULL, INDEX IDX_6707307CE2904019 (thread_id), INDEX IDX_6707307C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_comment ADD CONSTRAINT FK_6707307CE2904019 FOREIGN KEY (thread_id) REFERENCES comment_thread (id)');
        $this->addSql('ALTER TABLE comment_comment ADD CONSTRAINT FK_6707307C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment_comment (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_comment DROP FOREIGN KEY FK_6707307CE2904019');
        $this->addSql('ALTER TABLE comment_comment DROP FOREIGN KEY FK_6707307C727ACA70');
        $this->addSql('DROP TABLE comment_thread');
        $this->addSql('DROP TABLE comment_comment');
    }
}
