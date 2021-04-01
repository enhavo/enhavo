<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191026091722 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_thread ADD subjectClass VARCHAR(255) DEFAULT NULL, ADD subjectId VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_comment ADD user_id INT DEFAULT NULL, ADD state VARCHAR(255) DEFAULT NULL, ADD publishedAt DATETIME DEFAULT NULL, ADD name VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_comment ADD CONSTRAINT FK_6707307CA76ED395 FOREIGN KEY (user_id) REFERENCES user_user (id)');
        $this->addSql('CREATE INDEX IDX_6707307CA76ED395 ON comment_comment (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_comment DROP FOREIGN KEY FK_6707307CA76ED395');
        $this->addSql('DROP INDEX IDX_6707307CA76ED395 ON comment_comment');
        $this->addSql('ALTER TABLE comment_comment DROP user_id, DROP state, DROP publishedAt, DROP name, DROP email');
        $this->addSql('ALTER TABLE comment_thread DROP subjectClass, DROP subjectId');
    }
}
