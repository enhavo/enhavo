<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190715074658 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_article_teaser_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, article_id INT DEFAULT NULL, layout SMALLINT DEFAULT NULL, textLeft TINYINT(1) DEFAULT NULL, INDEX IDX_7E6A81C5460D9FD7 (node_id), INDEX IDX_7E6A81C57294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_article_teaser_block ADD CONSTRAINT FK_7E6A81C5460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_article_teaser_block ADD CONSTRAINT FK_7E6A81C57294869C FOREIGN KEY (article_id) REFERENCES article_article (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE article_article_teaser_block');
    }
}
