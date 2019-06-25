<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190624145745 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_article_stream DROP FOREIGN KEY FK_3C15A514E9ED820C');
        $this->addSql('ALTER TABLE block_block_cite DROP FOREIGN KEY FK_92D9076BE9ED820C');
        $this->addSql('ALTER TABLE block_block_gallery DROP FOREIGN KEY FK_44C5EF66E9ED820C');
        $this->addSql('ALTER TABLE block_block_one_column DROP FOREIGN KEY FK_CAD47894E9ED820C');
        $this->addSql('ALTER TABLE block_block_picture DROP FOREIGN KEY FK_1535D8D5E9ED820C');
        $this->addSql('ALTER TABLE block_block_text DROP FOREIGN KEY FK_9008FED7E9ED820C');
        $this->addSql('ALTER TABLE block_block_text_picture DROP FOREIGN KEY FK_675F3508E9ED820C');
        $this->addSql('ALTER TABLE block_block_three_column DROP FOREIGN KEY FK_86E4DE35E9ED820C');
        $this->addSql('ALTER TABLE block_block_two_column DROP FOREIGN KEY FK_8439EA69E9ED820C');
        $this->addSql('ALTER TABLE block_block_video DROP FOREIGN KEY FK_61DB4911E9ED820C');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column DROP FOREIGN KEY FK_75D34050E9ED820C');
        $this->addSql('ALTER TABLE block_block_gallery_file DROP FOREIGN KEY FK_4AA54B224E7AF8F');
        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD184A0A3ED');
        $this->addSql('ALTER TABLE block_block DROP FOREIGN KEY FK_440A51C4BC21F742');
        $this->addSql('ALTER TABLE block_block_one_column DROP FOREIGN KEY FK_CAD47894BE8E8ED5');
        $this->addSql('ALTER TABLE block_block_three_column DROP FOREIGN KEY FK_86E4DE3520157796');
        $this->addSql('ALTER TABLE block_block_three_column DROP FOREIGN KEY FK_86E4DE354B499059');
        $this->addSql('ALTER TABLE block_block_three_column DROP FOREIGN KEY FK_86E4DE35F1E466CA');
        $this->addSql('ALTER TABLE block_block_two_column DROP FOREIGN KEY FK_8439EA6920157796');
        $this->addSql('ALTER TABLE block_block_two_column DROP FOREIGN KEY FK_8439EA694B499059');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460F84A0A3ED');
        $this->addSql('ALTER TABLE newsletter_newsletter DROP FOREIGN KEY FK_9390BC6284A0A3ED');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA84A0A3ED');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column DROP FOREIGN KEY FK_75D34050BE8E8ED5');
        $this->addSql('ALTER TABLE sidebar_sidebar DROP FOREIGN KEY FK_E383352584A0A3ED');
        $this->addSql('ALTER TABLE template_template DROP FOREIGN KEY FK_C8362FF084A0A3ED');
        $this->addSql('CREATE TABLE block_node (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, blockId INT DEFAULT NULL, blockClass VARCHAR(255) DEFAULT NULL, property VARCHAR(255) DEFAULT NULL, enable TINYINT(1) DEFAULT NULL, INDEX IDX_4311BA3E727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blocl_text_picture_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, file_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, textLeft TINYINT(1) DEFAULT NULL, `float` TINYINT(1) DEFAULT NULL, layout INT DEFAULT NULL, INDEX IDX_DA5578B9460D9FD7 (node_id), UNIQUE INDEX UNIQ_DA5578B993CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_video_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_D674CB5B460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_text_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_B8422614460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_picture_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, file_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, INDEX IDX_7A0C73CE460D9FD7 (node_id), UNIQUE INDEX UNIQ_7A0C73CE93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_gallery_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_1E4B24A3460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_cite_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_D02CDA4460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_two_column_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, columnOne_id INT DEFAULT NULL, columnTwo_id INT DEFAULT NULL, INDEX IDX_3A725155460D9FD7 (node_id), INDEX IDX_3A7251554B499059 (columnOne_id), INDEX IDX_3A72515520157796 (columnTwo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_one_column_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, column_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, INDEX IDX_66A916CD460D9FD7 (node_id), INDEX IDX_66A916CDBE8E8ED5 (column_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_three_column_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, columnOne_id INT DEFAULT NULL, columnTwo_id INT DEFAULT NULL, columnThree_id INT DEFAULT NULL, INDEX IDX_BEA24F9B460D9FD7 (node_id), INDEX IDX_BEA24F9B4B499059 (columnOne_id), INDEX IDX_BEA24F9B20157796 (columnTwo_id), INDEX IDX_BEA24F9BF1E466CA (columnThree_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE block_node ADD CONSTRAINT FK_4311BA3E727ACA70 FOREIGN KEY (parent_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blocl_text_picture_block ADD CONSTRAINT FK_DA5578B9460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blocl_text_picture_block ADD CONSTRAINT FK_DA5578B993CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE block_video_block ADD CONSTRAINT FK_D674CB5B460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_text_block ADD CONSTRAINT FK_B8422614460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_picture_block ADD CONSTRAINT FK_7A0C73CE460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_picture_block ADD CONSTRAINT FK_7A0C73CE93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE block_gallery_block ADD CONSTRAINT FK_1E4B24A3460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_cite_block ADD CONSTRAINT FK_D02CDA4460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_two_column_block ADD CONSTRAINT FK_3A725155460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_two_column_block ADD CONSTRAINT FK_3A7251554B499059 FOREIGN KEY (columnOne_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_two_column_block ADD CONSTRAINT FK_3A72515520157796 FOREIGN KEY (columnTwo_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_one_column_block ADD CONSTRAINT FK_66A916CD460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_one_column_block ADD CONSTRAINT FK_66A916CDBE8E8ED5 FOREIGN KEY (column_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_three_column_block ADD CONSTRAINT FK_BEA24F9B460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_three_column_block ADD CONSTRAINT FK_BEA24F9B4B499059 FOREIGN KEY (columnOne_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_three_column_block ADD CONSTRAINT FK_BEA24F9B20157796 FOREIGN KEY (columnTwo_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_three_column_block ADD CONSTRAINT FK_BEA24F9BF1E466CA FOREIGN KEY (columnThree_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE block_block');
        $this->addSql('DROP TABLE block_block_cite');
        $this->addSql('DROP TABLE block_block_gallery');
        $this->addSql('DROP TABLE block_block_one_column');
        $this->addSql('DROP TABLE block_block_picture');
        $this->addSql('DROP TABLE block_block_text');
        $this->addSql('DROP TABLE block_block_text_picture');
        $this->addSql('DROP TABLE block_block_three_column');
        $this->addSql('DROP TABLE block_block_two_column');
        $this->addSql('DROP TABLE block_block_video');
        $this->addSql('DROP TABLE block_container');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD184A0A3ED FOREIGN KEY (content_id) REFERENCES block_node (id)');
        $this->addSql('DROP INDEX IDX_3C15A514E9ED820C ON article_article_stream');
        $this->addSql('ALTER TABLE article_article_stream CHANGE block_id node_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_article_stream ADD CONSTRAINT FK_3C15A514460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_3C15A514460D9FD7 ON article_article_stream (node_id)');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA84A0A3ED FOREIGN KEY (content_id) REFERENCES block_node (id)');
        $this->addSql('ALTER TABLE newsletter_newsletter ADD CONSTRAINT FK_9390BC6284A0A3ED FOREIGN KEY (content_id) REFERENCES block_node (id)');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460F84A0A3ED FOREIGN KEY (content_id) REFERENCES block_node (id)');
        $this->addSql('ALTER TABLE sidebar_sidebar ADD CONSTRAINT FK_E383352584A0A3ED FOREIGN KEY (content_id) REFERENCES block_node (id)');
        $this->addSql('DROP INDEX IDX_75D34050E9ED820C ON sidebar_block_sidebar_column');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column CHANGE block_id node_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D34050460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D34050BE8E8ED5 FOREIGN KEY (column_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_75D34050460D9FD7 ON sidebar_block_sidebar_column (node_id)');
        $this->addSql('ALTER TABLE template_template ADD CONSTRAINT FK_C8362FF084A0A3ED FOREIGN KEY (content_id) REFERENCES block_node (id)');
        $this->addSql('ALTER TABLE block_block_gallery_file ADD CONSTRAINT FK_4AA54B224E7AF8F FOREIGN KEY (gallery_id) REFERENCES block_gallery_block (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD184A0A3ED');
        $this->addSql('ALTER TABLE article_article_stream DROP FOREIGN KEY FK_3C15A514460D9FD7');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA84A0A3ED');
        $this->addSql('ALTER TABLE block_node DROP FOREIGN KEY FK_4311BA3E727ACA70');
        $this->addSql('ALTER TABLE newsletter_newsletter DROP FOREIGN KEY FK_9390BC6284A0A3ED');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460F84A0A3ED');
        $this->addSql('ALTER TABLE sidebar_sidebar DROP FOREIGN KEY FK_E383352584A0A3ED');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column DROP FOREIGN KEY FK_75D34050460D9FD7');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column DROP FOREIGN KEY FK_75D34050BE8E8ED5');
        $this->addSql('ALTER TABLE template_template DROP FOREIGN KEY FK_C8362FF084A0A3ED');
        $this->addSql('ALTER TABLE blocl_text_picture_block DROP FOREIGN KEY FK_DA5578B9460D9FD7');
        $this->addSql('ALTER TABLE block_video_block DROP FOREIGN KEY FK_D674CB5B460D9FD7');
        $this->addSql('ALTER TABLE block_text_block DROP FOREIGN KEY FK_B8422614460D9FD7');
        $this->addSql('ALTER TABLE block_picture_block DROP FOREIGN KEY FK_7A0C73CE460D9FD7');
        $this->addSql('ALTER TABLE block_gallery_block DROP FOREIGN KEY FK_1E4B24A3460D9FD7');
        $this->addSql('ALTER TABLE block_cite_block DROP FOREIGN KEY FK_D02CDA4460D9FD7');
        $this->addSql('ALTER TABLE block_two_column_block DROP FOREIGN KEY FK_3A725155460D9FD7');
        $this->addSql('ALTER TABLE block_two_column_block DROP FOREIGN KEY FK_3A7251554B499059');
        $this->addSql('ALTER TABLE block_two_column_block DROP FOREIGN KEY FK_3A72515520157796');
        $this->addSql('ALTER TABLE block_one_column_block DROP FOREIGN KEY FK_66A916CD460D9FD7');
        $this->addSql('ALTER TABLE block_one_column_block DROP FOREIGN KEY FK_66A916CDBE8E8ED5');
        $this->addSql('ALTER TABLE block_three_column_block DROP FOREIGN KEY FK_BEA24F9B460D9FD7');
        $this->addSql('ALTER TABLE block_three_column_block DROP FOREIGN KEY FK_BEA24F9B4B499059');
        $this->addSql('ALTER TABLE block_three_column_block DROP FOREIGN KEY FK_BEA24F9B20157796');
        $this->addSql('ALTER TABLE block_three_column_block DROP FOREIGN KEY FK_BEA24F9BF1E466CA');
        $this->addSql('ALTER TABLE block_block_gallery_file DROP FOREIGN KEY FK_4AA54B224E7AF8F');
        $this->addSql('CREATE TABLE block_block (id INT AUTO_INCREMENT NOT NULL, container_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, position INT DEFAULT NULL, blockTypeId INT DEFAULT NULL, blockTypeClass VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_440A51C4BC21F742 (container_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_cite (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_92D9076BE9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_gallery (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_44C5EF66E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_one_column (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, column_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, style VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_CAD47894BE8E8ED5 (column_id), INDEX IDX_CAD47894E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_picture (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, file_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, caption VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_1535D8D5E9ED820C (block_id), UNIQUE INDEX UNIQ_1535D8D593CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_text (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_9008FED7E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_text_picture (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, file_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, caption VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, textLeft TINYINT(1) DEFAULT NULL, `float` TINYINT(1) DEFAULT NULL, layout INT DEFAULT NULL, INDEX IDX_675F3508E9ED820C (block_id), UNIQUE INDEX UNIQ_675F350893CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_three_column (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, style VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, columnOne_id INT DEFAULT NULL, columnTwo_id INT DEFAULT NULL, columnThree_id INT DEFAULT NULL, INDEX IDX_86E4DE354B499059 (columnOne_id), INDEX IDX_86E4DE35F1E466CA (columnThree_id), INDEX IDX_86E4DE35E9ED820C (block_id), INDEX IDX_86E4DE3520157796 (columnTwo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_two_column (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, style VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, columnOne_id INT DEFAULT NULL, columnTwo_id INT DEFAULT NULL, INDEX IDX_8439EA694B499059 (columnOne_id), INDEX IDX_8439EA69E9ED820C (block_id), INDEX IDX_8439EA6920157796 (columnTwo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_block_video (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_61DB4911E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE block_container (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE block_block ADD CONSTRAINT FK_440A51C4BC21F742 FOREIGN KEY (container_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_cite ADD CONSTRAINT FK_92D9076BE9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_gallery ADD CONSTRAINT FK_44C5EF66E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_one_column ADD CONSTRAINT FK_CAD47894BE8E8ED5 FOREIGN KEY (column_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_one_column ADD CONSTRAINT FK_CAD47894E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_picture ADD CONSTRAINT FK_1535D8D593CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE block_block_picture ADD CONSTRAINT FK_1535D8D5E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_text ADD CONSTRAINT FK_9008FED7E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_text_picture ADD CONSTRAINT FK_675F350893CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE block_block_text_picture ADD CONSTRAINT FK_675F3508E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_three_column ADD CONSTRAINT FK_86E4DE3520157796 FOREIGN KEY (columnTwo_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_three_column ADD CONSTRAINT FK_86E4DE354B499059 FOREIGN KEY (columnOne_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_three_column ADD CONSTRAINT FK_86E4DE35E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_three_column ADD CONSTRAINT FK_86E4DE35F1E466CA FOREIGN KEY (columnThree_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_two_column ADD CONSTRAINT FK_8439EA6920157796 FOREIGN KEY (columnTwo_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_two_column ADD CONSTRAINT FK_8439EA694B499059 FOREIGN KEY (columnOne_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_two_column ADD CONSTRAINT FK_8439EA69E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_video ADD CONSTRAINT FK_61DB4911E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE block_node');
        $this->addSql('DROP TABLE blocl_text_picture_block');
        $this->addSql('DROP TABLE block_video_block');
        $this->addSql('DROP TABLE block_text_block');
        $this->addSql('DROP TABLE block_picture_block');
        $this->addSql('DROP TABLE block_gallery_block');
        $this->addSql('DROP TABLE block_cite_block');
        $this->addSql('DROP TABLE block_two_column_block');
        $this->addSql('DROP TABLE block_one_column_block');
        $this->addSql('DROP TABLE block_three_column_block');
        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD184A0A3ED');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD184A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('DROP INDEX IDX_3C15A514460D9FD7 ON article_article_stream');
        $this->addSql('ALTER TABLE article_article_stream CHANGE node_id block_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_article_stream ADD CONSTRAINT FK_3C15A514E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_3C15A514E9ED820C ON article_article_stream (block_id)');
        $this->addSql('ALTER TABLE block_block_gallery_file DROP FOREIGN KEY FK_4AA54B224E7AF8F');
        $this->addSql('ALTER TABLE block_block_gallery_file ADD CONSTRAINT FK_4AA54B224E7AF8F FOREIGN KEY (gallery_id) REFERENCES block_block_gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460F84A0A3ED');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460F84A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('ALTER TABLE newsletter_newsletter DROP FOREIGN KEY FK_9390BC6284A0A3ED');
        $this->addSql('ALTER TABLE newsletter_newsletter ADD CONSTRAINT FK_9390BC6284A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA84A0A3ED');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA84A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column DROP FOREIGN KEY FK_75D34050BE8E8ED5');
        $this->addSql('DROP INDEX IDX_75D34050460D9FD7 ON sidebar_block_sidebar_column');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column CHANGE node_id block_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D34050E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D34050BE8E8ED5 FOREIGN KEY (column_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_75D34050E9ED820C ON sidebar_block_sidebar_column (block_id)');
        $this->addSql('ALTER TABLE sidebar_sidebar DROP FOREIGN KEY FK_E383352584A0A3ED');
        $this->addSql('ALTER TABLE sidebar_sidebar ADD CONSTRAINT FK_E383352584A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('ALTER TABLE template_template DROP FOREIGN KEY FK_C8362FF084A0A3ED');
        $this->addSql('ALTER TABLE template_template ADD CONSTRAINT FK_C8362FF084A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
    }
}
