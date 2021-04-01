<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190624165619 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_article_stream_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, `limit` INT DEFAULT NULL, pagination TINYINT(1) DEFAULT NULL, INDEX IDX_6315908C460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sidebar_sidebar_column_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, column_id INT DEFAULT NULL, sidebar_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, INDEX IDX_F40A1E75460D9FD7 (node_id), INDEX IDX_F40A1E75BE8E8ED5 (column_id), INDEX IDX_F40A1E753A432888 (sidebar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_text_picture_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, file_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, textLeft TINYINT(1) DEFAULT NULL, `float` TINYINT(1) DEFAULT NULL, layout INT DEFAULT NULL, INDEX IDX_3B11DE2A460D9FD7 (node_id), UNIQUE INDEX UNIQ_3B11DE2A93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_gallery_block_file (gallery_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_61E3C6DA4E7AF8F (gallery_id), INDEX IDX_61E3C6DA93CB796C (file_id), PRIMARY KEY(gallery_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_article_stream_block ADD CONSTRAINT FK_6315908C460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_sidebar_column_block ADD CONSTRAINT FK_F40A1E75460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_sidebar_column_block ADD CONSTRAINT FK_F40A1E75BE8E8ED5 FOREIGN KEY (column_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_sidebar_column_block ADD CONSTRAINT FK_F40A1E753A432888 FOREIGN KEY (sidebar_id) REFERENCES sidebar_sidebar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_text_picture_block ADD CONSTRAINT FK_3B11DE2A460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_text_picture_block ADD CONSTRAINT FK_3B11DE2A93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE block_gallery_block_file ADD CONSTRAINT FK_61E3C6DA4E7AF8F FOREIGN KEY (gallery_id) REFERENCES block_gallery_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_gallery_block_file ADD CONSTRAINT FK_61E3C6DA93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE article_article_stream');
        $this->addSql('DROP TABLE block_block_gallery_file');
        $this->addSql('DROP TABLE blocl_text_picture_block');
        $this->addSql('DROP TABLE sidebar_block_sidebar_column');
        $this->addSql('ALTER TABLE block_node ADD type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_article_stream (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, `limit` INT DEFAULT NULL, pagination TINYINT(1) DEFAULT NULL, INDEX IDX_3C15A514460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_gallery_file (gallery_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_4AA54B224E7AF8F (gallery_id), INDEX IDX_4AA54B2293CB796C (file_id), PRIMARY KEY(gallery_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE blocl_text_picture_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, file_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, caption VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, textLeft TINYINT(1) DEFAULT NULL, `float` TINYINT(1) DEFAULT NULL, layout INT DEFAULT NULL, INDEX IDX_DA5578B9460D9FD7 (node_id), UNIQUE INDEX UNIQ_DA5578B993CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sidebar_block_sidebar_column (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, column_id INT DEFAULT NULL, sidebar_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, style VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_75D340503A432888 (sidebar_id), INDEX IDX_75D34050BE8E8ED5 (column_id), INDEX IDX_75D34050460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_article_stream ADD CONSTRAINT FK_3C15A514460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_gallery_file ADD CONSTRAINT FK_4AA54B224E7AF8F FOREIGN KEY (gallery_id) REFERENCES block_gallery_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_gallery_file ADD CONSTRAINT FK_4AA54B2293CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blocl_text_picture_block ADD CONSTRAINT FK_DA5578B9460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blocl_text_picture_block ADD CONSTRAINT FK_DA5578B993CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D340503A432888 FOREIGN KEY (sidebar_id) REFERENCES sidebar_sidebar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D34050460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D34050BE8E8ED5 FOREIGN KEY (column_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE article_article_stream_block');
        $this->addSql('DROP TABLE sidebar_sidebar_column_block');
        $this->addSql('DROP TABLE block_text_picture_block');
        $this->addSql('DROP TABLE block_gallery_block_file');
        $this->addSql('ALTER TABLE block_node DROP type');
    }
}
