<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190530140609 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_category DROP FOREIGN KEY FK_53A4EDAA12469DE2');
        $this->addSql('ALTER TABLE category_category DROP FOREIGN KEY FK_B1369DBA514956FD');
        $this->addSql('ALTER TABLE project_content DROP FOREIGN KEY FK_68DB3CCC3EB84A1D');
        $this->addSql('ALTER TABLE project_magazine_pictures DROP FOREIGN KEY FK_842A21113EB84A1D');
        $this->addSql('CREATE TABLE article_article_category (article_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_44F096D97294869C (article_id), INDEX IDX_44F096D912469DE2 (category_id), PRIMARY KEY(article_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_article_tag (article_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_B15FE9E7294869C (article_id), INDEX IDX_B15FE9E12469DE2 (category_id), PRIMARY KEY(article_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_article_stream (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, `limit` INT DEFAULT NULL, pagination TINYINT(1) DEFAULT NULL, INDEX IDX_3C15A514126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxonomy_term (id INT AUTO_INCREMENT NOT NULL, taxonomy_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, position INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_C7ED653A9557E6F6 (taxonomy_id), INDEX IDX_C7ED653A727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxonomy_taxonomy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_article_category ADD CONSTRAINT FK_44F096D97294869C FOREIGN KEY (article_id) REFERENCES article_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_article_category ADD CONSTRAINT FK_44F096D912469DE2 FOREIGN KEY (category_id) REFERENCES taxonomy_term (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_article_tag ADD CONSTRAINT FK_B15FE9E7294869C FOREIGN KEY (article_id) REFERENCES article_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_article_tag ADD CONSTRAINT FK_B15FE9E12469DE2 FOREIGN KEY (category_id) REFERENCES taxonomy_term (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_article_stream ADD CONSTRAINT FK_3C15A514126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE taxonomy_term ADD CONSTRAINT FK_C7ED653A9557E6F6 FOREIGN KEY (taxonomy_id) REFERENCES taxonomy_taxonomy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE taxonomy_term ADD CONSTRAINT FK_C7ED653A727ACA70 FOREIGN KEY (parent_id) REFERENCES taxonomy_term (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE article_category');
        $this->addSql('DROP TABLE category_category');
        $this->addSql('DROP TABLE category_collection');
        $this->addSql('DROP TABLE project_content');
        $this->addSql('DROP TABLE project_magazine');
        $this->addSql('DROP TABLE project_magazine_pictures');
        $this->addSql('ALTER TABLE newsletter_group ADD discr VARCHAR(6) NOT NULL, DROP code');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_article_category DROP FOREIGN KEY FK_44F096D912469DE2');
        $this->addSql('ALTER TABLE article_article_tag DROP FOREIGN KEY FK_B15FE9E12469DE2');
        $this->addSql('ALTER TABLE taxonomy_term DROP FOREIGN KEY FK_C7ED653A727ACA70');
        $this->addSql('ALTER TABLE taxonomy_term DROP FOREIGN KEY FK_C7ED653A9557E6F6');
        $this->addSql('CREATE TABLE article_category (article_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_53A4EDAA7294869C (article_id), INDEX IDX_53A4EDAA12469DE2 (category_id), PRIMARY KEY(article_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE category_category (id INT AUTO_INCREMENT NOT NULL, picture_id INT DEFAULT NULL, collection_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, `order` INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_B1369DBA514956FD (collection_id), UNIQUE INDEX UNIQ_B1369DBAEE45BDBF (picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE category_collection (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE project_content (id INT AUTO_INCREMENT NOT NULL, magazine_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, teaser LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, tags LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', position INT DEFAULT NULL, INDEX IDX_68DB3CCC3EB84A1D (magazine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE project_magazine (id INT AUTO_INCREMENT NOT NULL, cover_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, tags LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', INDEX IDX_AC2A43F922726E9 (cover_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE project_magazine_pictures (magazine_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_842A21113EB84A1D (magazine_id), INDEX IDX_842A211193CB796C (file_id), PRIMARY KEY(magazine_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_category ADD CONSTRAINT FK_53A4EDAA12469DE2 FOREIGN KEY (category_id) REFERENCES category_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_category ADD CONSTRAINT FK_53A4EDAA7294869C FOREIGN KEY (article_id) REFERENCES article_article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_category ADD CONSTRAINT FK_B1369DBA514956FD FOREIGN KEY (collection_id) REFERENCES category_collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_category ADD CONSTRAINT FK_B1369DBAEE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE project_content ADD CONSTRAINT FK_68DB3CCC3EB84A1D FOREIGN KEY (magazine_id) REFERENCES project_magazine (id)');
        $this->addSql('ALTER TABLE project_magazine ADD CONSTRAINT FK_AC2A43F922726E9 FOREIGN KEY (cover_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE project_magazine_pictures ADD CONSTRAINT FK_842A21113EB84A1D FOREIGN KEY (magazine_id) REFERENCES project_magazine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_magazine_pictures ADD CONSTRAINT FK_842A211193CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE article_article_category');
        $this->addSql('DROP TABLE article_article_tag');
        $this->addSql('DROP TABLE article_article_stream');
        $this->addSql('DROP TABLE taxonomy_term');
        $this->addSql('DROP TABLE taxonomy_taxonomy');
        $this->addSql('ALTER TABLE newsletter_group ADD code LONGTEXT NOT NULL COLLATE utf8_unicode_ci, DROP discr');
    }
}
