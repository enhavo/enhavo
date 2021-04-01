<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190916143041 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_article ADD thread_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD1E2904019 FOREIGN KEY (thread_id) REFERENCES comment_thread (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EFE84AD1E2904019 ON article_article (thread_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD1E2904019');
        $this->addSql('DROP INDEX UNIQ_EFE84AD1E2904019 ON article_article');
        $this->addSql('ALTER TABLE article_article DROP thread_id');
    }
}
