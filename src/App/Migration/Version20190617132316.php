<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190617132316 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD12CF16895');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460F2CF16895');
        $this->addSql('ALTER TABLE grid_item DROP FOREIGN KEY FK_3929884C2CF16895');
        $this->addSql('ALTER TABLE grid_item_one_column DROP FOREIGN KEY FK_28DB32EABE8E8ED5');
        $this->addSql('ALTER TABLE grid_item_three_column DROP FOREIGN KEY FK_A151C0CC20157796');
        $this->addSql('ALTER TABLE grid_item_three_column DROP FOREIGN KEY FK_A151C0CC4B499059');
        $this->addSql('ALTER TABLE grid_item_three_column DROP FOREIGN KEY FK_A151C0CCF1E466CA');
        $this->addSql('ALTER TABLE grid_item_two_column DROP FOREIGN KEY FK_6636A01720157796');
        $this->addSql('ALTER TABLE grid_item_two_column DROP FOREIGN KEY FK_6636A0174B499059');
        $this->addSql('ALTER TABLE newsletter_newsletter DROP FOREIGN KEY FK_9390BC622CF16895');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA2CF16895');
        $this->addSql('ALTER TABLE sidebar_item_sidebar_column DROP FOREIGN KEY FK_6C0652D8BE8E8ED5');
        $this->addSql('ALTER TABLE sidebar_sidebar DROP FOREIGN KEY FK_E38335252CF16895');
        $this->addSql('ALTER TABLE template_template DROP FOREIGN KEY FK_C8362FF02CF16895');
        $this->addSql('ALTER TABLE article_article_stream DROP FOREIGN KEY FK_3C15A514126F525E');
        $this->addSql('ALTER TABLE grid_item_cite DROP FOREIGN KEY FK_E889F529126F525E');
        $this->addSql('ALTER TABLE grid_item_gallery DROP FOREIGN KEY FK_7A3A6479126F525E');
        $this->addSql('ALTER TABLE grid_item_one_column DROP FOREIGN KEY FK_28DB32EA126F525E');
        $this->addSql('ALTER TABLE grid_item_picture DROP FOREIGN KEY FK_2BCA53CA126F525E');
        $this->addSql('ALTER TABLE grid_item_text DROP FOREIGN KEY FK_EA580C95126F525E');
        $this->addSql('ALTER TABLE grid_item_text_picture DROP FOREIGN KEY FK_40EA2BF1126F525E');
        $this->addSql('ALTER TABLE grid_item_three_column DROP FOREIGN KEY FK_A151C0CC126F525E');
        $this->addSql('ALTER TABLE grid_item_two_column DROP FOREIGN KEY FK_6636A017126F525E');
        $this->addSql('ALTER TABLE grid_item_video DROP FOREIGN KEY FK_F973395F126F525E');
        $this->addSql('ALTER TABLE sidebar_item_sidebar_column DROP FOREIGN KEY FK_6C0652D8126F525E');
        $this->addSql('ALTER TABLE grid_item_gallery_file DROP FOREIGN KEY FK_6D1055DB4E7AF8F');
        $this->addSql('CREATE TABLE block_container (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block (id INT AUTO_INCREMENT NOT NULL, container_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, blockTypeId INT DEFAULT NULL, blockTypeClass VARCHAR(255) DEFAULT NULL, INDEX IDX_440A51C4BC21F742 (container_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sidebar_block_sidebar_column (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, column_id INT DEFAULT NULL, sidebar_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, INDEX IDX_75D34050E9ED820C (block_id), INDEX IDX_75D34050BE8E8ED5 (column_id), INDEX IDX_75D340503A432888 (sidebar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_text_picture (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, file_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, textLeft TINYINT(1) DEFAULT NULL, `float` TINYINT(1) DEFAULT NULL, layout INT DEFAULT NULL, INDEX IDX_675F3508E9ED820C (block_id), UNIQUE INDEX UNIQ_675F350893CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_video (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_61DB4911E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_text (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_9008FED7E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_picture (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, file_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, INDEX IDX_1535D8D5E9ED820C (block_id), UNIQUE INDEX UNIQ_1535D8D593CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_gallery (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_44C5EF66E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_gallery_file (gallery_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_4AA54B224E7AF8F (gallery_id), INDEX IDX_4AA54B2293CB796C (file_id), PRIMARY KEY(gallery_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_cite (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_92D9076BE9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_two_column (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, columnOne_id INT DEFAULT NULL, columnTwo_id INT DEFAULT NULL, INDEX IDX_8439EA69E9ED820C (block_id), INDEX IDX_8439EA694B499059 (columnOne_id), INDEX IDX_8439EA6920157796 (columnTwo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_one_column (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, column_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, INDEX IDX_CAD47894E9ED820C (block_id), INDEX IDX_CAD47894BE8E8ED5 (column_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_block_three_column (id INT AUTO_INCREMENT NOT NULL, block_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL, style VARCHAR(255) DEFAULT NULL, columnOne_id INT DEFAULT NULL, columnTwo_id INT DEFAULT NULL, columnThree_id INT DEFAULT NULL, INDEX IDX_86E4DE35E9ED820C (block_id), INDEX IDX_86E4DE354B499059 (columnOne_id), INDEX IDX_86E4DE3520157796 (columnTwo_id), INDEX IDX_86E4DE35F1E466CA (columnThree_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE block_block ADD CONSTRAINT FK_440A51C4BC21F742 FOREIGN KEY (container_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D34050E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D34050BE8E8ED5 FOREIGN KEY (column_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column ADD CONSTRAINT FK_75D340503A432888 FOREIGN KEY (sidebar_id) REFERENCES sidebar_sidebar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_text_picture ADD CONSTRAINT FK_675F3508E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_text_picture ADD CONSTRAINT FK_675F350893CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE block_block_video ADD CONSTRAINT FK_61DB4911E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_text ADD CONSTRAINT FK_9008FED7E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_picture ADD CONSTRAINT FK_1535D8D5E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_picture ADD CONSTRAINT FK_1535D8D593CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE block_block_gallery ADD CONSTRAINT FK_44C5EF66E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_gallery_file ADD CONSTRAINT FK_4AA54B224E7AF8F FOREIGN KEY (gallery_id) REFERENCES block_block_gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_gallery_file ADD CONSTRAINT FK_4AA54B2293CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_cite ADD CONSTRAINT FK_92D9076BE9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_two_column ADD CONSTRAINT FK_8439EA69E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_two_column ADD CONSTRAINT FK_8439EA694B499059 FOREIGN KEY (columnOne_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_two_column ADD CONSTRAINT FK_8439EA6920157796 FOREIGN KEY (columnTwo_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_one_column ADD CONSTRAINT FK_CAD47894E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_one_column ADD CONSTRAINT FK_CAD47894BE8E8ED5 FOREIGN KEY (column_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_three_column ADD CONSTRAINT FK_86E4DE35E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_three_column ADD CONSTRAINT FK_86E4DE354B499059 FOREIGN KEY (columnOne_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_three_column ADD CONSTRAINT FK_86E4DE3520157796 FOREIGN KEY (columnTwo_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_block_three_column ADD CONSTRAINT FK_86E4DE35F1E466CA FOREIGN KEY (columnThree_id) REFERENCES block_container (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE grid_grid');
        $this->addSql('DROP TABLE grid_item');
        $this->addSql('DROP TABLE grid_item_cite');
        $this->addSql('DROP TABLE grid_item_gallery');
        $this->addSql('DROP TABLE grid_item_gallery_file');
        $this->addSql('DROP TABLE grid_item_one_column');
        $this->addSql('DROP TABLE grid_item_picture');
        $this->addSql('DROP TABLE grid_item_text');
        $this->addSql('DROP TABLE grid_item_text_picture');
        $this->addSql('DROP TABLE grid_item_three_column');
        $this->addSql('DROP TABLE grid_item_two_column');
        $this->addSql('DROP TABLE grid_item_video');
        $this->addSql('DROP TABLE sidebar_item_sidebar_column');
        $this->addSql('DROP INDEX UNIQ_EFE84AD12CF16895 ON article_article');
        $this->addSql('ALTER TABLE article_article CHANGE grid_id content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD184A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EFE84AD184A0A3ED ON article_article (content_id)');
        $this->addSql('DROP INDEX IDX_3C15A514126F525E ON article_article_stream');
        $this->addSql('ALTER TABLE article_article_stream CHANGE item_id block_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_article_stream ADD CONSTRAINT FK_3C15A514E9ED820C FOREIGN KEY (block_id) REFERENCES block_block (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_3C15A514E9ED820C ON article_article_stream (block_id)');
        $this->addSql('DROP INDEX UNIQ_93CEAAFA2CF16895 ON page_page');
        $this->addSql('ALTER TABLE page_page CHANGE grid_id content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA84A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_93CEAAFA84A0A3ED ON page_page (content_id)');
        $this->addSql('DROP INDEX UNIQ_9390BC622CF16895 ON newsletter_newsletter');
        $this->addSql('ALTER TABLE newsletter_newsletter CHANGE grid_id content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter_newsletter ADD CONSTRAINT FK_9390BC6284A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9390BC6284A0A3ED ON newsletter_newsletter (content_id)');
        $this->addSql('DROP INDEX UNIQ_8EC4460F2CF16895 ON calendar_appointment');
        $this->addSql('ALTER TABLE calendar_appointment CHANGE grid_id content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460F84A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8EC4460F84A0A3ED ON calendar_appointment (content_id)');
        $this->addSql('DROP INDEX UNIQ_E38335252CF16895 ON sidebar_sidebar');
        $this->addSql('ALTER TABLE sidebar_sidebar CHANGE grid_id content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sidebar_sidebar ADD CONSTRAINT FK_E383352584A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E383352584A0A3ED ON sidebar_sidebar (content_id)');
        $this->addSql('DROP INDEX UNIQ_C8362FF02CF16895 ON template_template');
        $this->addSql('ALTER TABLE template_template CHANGE grid_id content_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE template_template ADD CONSTRAINT FK_C8362FF084A0A3ED FOREIGN KEY (content_id) REFERENCES block_container (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8362FF084A0A3ED ON template_template (content_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD184A0A3ED');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA84A0A3ED');
        $this->addSql('ALTER TABLE block_block DROP FOREIGN KEY FK_440A51C4BC21F742');
        $this->addSql('ALTER TABLE newsletter_newsletter DROP FOREIGN KEY FK_9390BC6284A0A3ED');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460F84A0A3ED');
        $this->addSql('ALTER TABLE sidebar_sidebar DROP FOREIGN KEY FK_E383352584A0A3ED');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column DROP FOREIGN KEY FK_75D34050BE8E8ED5');
        $this->addSql('ALTER TABLE template_template DROP FOREIGN KEY FK_C8362FF084A0A3ED');
        $this->addSql('ALTER TABLE block_block_two_column DROP FOREIGN KEY FK_8439EA694B499059');
        $this->addSql('ALTER TABLE block_block_two_column DROP FOREIGN KEY FK_8439EA6920157796');
        $this->addSql('ALTER TABLE block_block_one_column DROP FOREIGN KEY FK_CAD47894BE8E8ED5');
        $this->addSql('ALTER TABLE block_block_three_column DROP FOREIGN KEY FK_86E4DE354B499059');
        $this->addSql('ALTER TABLE block_block_three_column DROP FOREIGN KEY FK_86E4DE3520157796');
        $this->addSql('ALTER TABLE block_block_three_column DROP FOREIGN KEY FK_86E4DE35F1E466CA');
        $this->addSql('ALTER TABLE article_article_stream DROP FOREIGN KEY FK_3C15A514E9ED820C');
        $this->addSql('ALTER TABLE sidebar_block_sidebar_column DROP FOREIGN KEY FK_75D34050E9ED820C');
        $this->addSql('ALTER TABLE block_block_text_picture DROP FOREIGN KEY FK_675F3508E9ED820C');
        $this->addSql('ALTER TABLE block_block_video DROP FOREIGN KEY FK_61DB4911E9ED820C');
        $this->addSql('ALTER TABLE block_block_text DROP FOREIGN KEY FK_9008FED7E9ED820C');
        $this->addSql('ALTER TABLE block_block_picture DROP FOREIGN KEY FK_1535D8D5E9ED820C');
        $this->addSql('ALTER TABLE block_block_gallery DROP FOREIGN KEY FK_44C5EF66E9ED820C');
        $this->addSql('ALTER TABLE block_block_cite DROP FOREIGN KEY FK_92D9076BE9ED820C');
        $this->addSql('ALTER TABLE block_block_two_column DROP FOREIGN KEY FK_8439EA69E9ED820C');
        $this->addSql('ALTER TABLE block_block_one_column DROP FOREIGN KEY FK_CAD47894E9ED820C');
        $this->addSql('ALTER TABLE block_block_three_column DROP FOREIGN KEY FK_86E4DE35E9ED820C');
        $this->addSql('ALTER TABLE block_block_gallery_file DROP FOREIGN KEY FK_4AA54B224E7AF8F');
        $this->addSql('CREATE TABLE grid_grid (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item (id INT AUTO_INCREMENT NOT NULL, grid_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, position INT DEFAULT NULL, itemTypeId INT DEFAULT NULL, itemTypeClass VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_3929884C2CF16895 (grid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_cite (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_E889F529126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_gallery (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_7A3A6479126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_gallery_file (gallery_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_6D1055DB4E7AF8F (gallery_id), INDEX IDX_6D1055DB93CB796C (file_id), PRIMARY KEY(gallery_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_one_column (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, column_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, style VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_28DB32EABE8E8ED5 (column_id), INDEX IDX_28DB32EA126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_picture (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, file_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, caption VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_2BCA53CA126F525E (item_id), UNIQUE INDEX UNIQ_2BCA53CA93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_text (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_EA580C95126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_text_picture (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, file_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, caption VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, textLeft TINYINT(1) DEFAULT NULL, `float` TINYINT(1) DEFAULT NULL, layout INT DEFAULT NULL, INDEX IDX_40EA2BF1126F525E (item_id), UNIQUE INDEX UNIQ_40EA2BF193CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_three_column (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, style VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, columnOne_id INT DEFAULT NULL, columnTwo_id INT DEFAULT NULL, columnThree_id INT DEFAULT NULL, INDEX IDX_A151C0CC4B499059 (columnOne_id), INDEX IDX_A151C0CCF1E466CA (columnThree_id), INDEX IDX_A151C0CC126F525E (item_id), INDEX IDX_A151C0CC20157796 (columnTwo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_two_column (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, style VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, columnOne_id INT DEFAULT NULL, columnTwo_id INT DEFAULT NULL, INDEX IDX_6636A0174B499059 (columnOne_id), INDEX IDX_6636A017126F525E (item_id), INDEX IDX_6636A01720157796 (columnTwo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grid_item_video (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_F973395F126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sidebar_item_sidebar_column (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, column_id INT DEFAULT NULL, sidebar_id INT DEFAULT NULL, width VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, style VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_6C0652D8BE8E8ED5 (column_id), INDEX IDX_6C0652D8126F525E (item_id), INDEX IDX_6C0652D83A432888 (sidebar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE grid_item ADD CONSTRAINT FK_3929884C2CF16895 FOREIGN KEY (grid_id) REFERENCES grid_grid (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_cite ADD CONSTRAINT FK_E889F529126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_gallery ADD CONSTRAINT FK_7A3A6479126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_gallery_file ADD CONSTRAINT FK_6D1055DB4E7AF8F FOREIGN KEY (gallery_id) REFERENCES grid_item_gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_gallery_file ADD CONSTRAINT FK_6D1055DB93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_one_column ADD CONSTRAINT FK_28DB32EA126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_one_column ADD CONSTRAINT FK_28DB32EABE8E8ED5 FOREIGN KEY (column_id) REFERENCES grid_grid (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_picture ADD CONSTRAINT FK_2BCA53CA126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_picture ADD CONSTRAINT FK_2BCA53CA93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE grid_item_text ADD CONSTRAINT FK_EA580C95126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_text_picture ADD CONSTRAINT FK_40EA2BF1126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_text_picture ADD CONSTRAINT FK_40EA2BF193CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE grid_item_three_column ADD CONSTRAINT FK_A151C0CC126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_three_column ADD CONSTRAINT FK_A151C0CC20157796 FOREIGN KEY (columnTwo_id) REFERENCES grid_grid (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_three_column ADD CONSTRAINT FK_A151C0CC4B499059 FOREIGN KEY (columnOne_id) REFERENCES grid_grid (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_three_column ADD CONSTRAINT FK_A151C0CCF1E466CA FOREIGN KEY (columnThree_id) REFERENCES grid_grid (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_two_column ADD CONSTRAINT FK_6636A017126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_two_column ADD CONSTRAINT FK_6636A01720157796 FOREIGN KEY (columnTwo_id) REFERENCES grid_grid (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_two_column ADD CONSTRAINT FK_6636A0174B499059 FOREIGN KEY (columnOne_id) REFERENCES grid_grid (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE grid_item_video ADD CONSTRAINT FK_F973395F126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_item_sidebar_column ADD CONSTRAINT FK_6C0652D8126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_item_sidebar_column ADD CONSTRAINT FK_6C0652D83A432888 FOREIGN KEY (sidebar_id) REFERENCES sidebar_sidebar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sidebar_item_sidebar_column ADD CONSTRAINT FK_6C0652D8BE8E8ED5 FOREIGN KEY (column_id) REFERENCES grid_grid (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE block_container');
        $this->addSql('DROP TABLE block_block');
        $this->addSql('DROP TABLE sidebar_block_sidebar_column');
        $this->addSql('DROP TABLE block_block_text_picture');
        $this->addSql('DROP TABLE block_block_video');
        $this->addSql('DROP TABLE block_block_text');
        $this->addSql('DROP TABLE block_block_picture');
        $this->addSql('DROP TABLE block_block_gallery');
        $this->addSql('DROP TABLE block_block_gallery_file');
        $this->addSql('DROP TABLE block_block_cite');
        $this->addSql('DROP TABLE block_block_two_column');
        $this->addSql('DROP TABLE block_block_one_column');
        $this->addSql('DROP TABLE block_block_three_column');
        $this->addSql('DROP INDEX UNIQ_EFE84AD184A0A3ED ON article_article');
        $this->addSql('ALTER TABLE article_article CHANGE content_id grid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD12CF16895 FOREIGN KEY (grid_id) REFERENCES grid_grid (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EFE84AD12CF16895 ON article_article (grid_id)');
        $this->addSql('DROP INDEX IDX_3C15A514E9ED820C ON article_article_stream');
        $this->addSql('ALTER TABLE article_article_stream CHANGE block_id item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_article_stream ADD CONSTRAINT FK_3C15A514126F525E FOREIGN KEY (item_id) REFERENCES grid_item (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_3C15A514126F525E ON article_article_stream (item_id)');
        $this->addSql('DROP INDEX UNIQ_8EC4460F84A0A3ED ON calendar_appointment');
        $this->addSql('ALTER TABLE calendar_appointment CHANGE content_id grid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460F2CF16895 FOREIGN KEY (grid_id) REFERENCES grid_grid (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8EC4460F2CF16895 ON calendar_appointment (grid_id)');
        $this->addSql('DROP INDEX UNIQ_9390BC6284A0A3ED ON newsletter_newsletter');
        $this->addSql('ALTER TABLE newsletter_newsletter CHANGE content_id grid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter_newsletter ADD CONSTRAINT FK_9390BC622CF16895 FOREIGN KEY (grid_id) REFERENCES grid_grid (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9390BC622CF16895 ON newsletter_newsletter (grid_id)');
        $this->addSql('DROP INDEX UNIQ_93CEAAFA84A0A3ED ON page_page');
        $this->addSql('ALTER TABLE page_page CHANGE content_id grid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA2CF16895 FOREIGN KEY (grid_id) REFERENCES grid_grid (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_93CEAAFA2CF16895 ON page_page (grid_id)');
        $this->addSql('DROP INDEX UNIQ_E383352584A0A3ED ON sidebar_sidebar');
        $this->addSql('ALTER TABLE sidebar_sidebar CHANGE content_id grid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sidebar_sidebar ADD CONSTRAINT FK_E38335252CF16895 FOREIGN KEY (grid_id) REFERENCES grid_grid (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E38335252CF16895 ON sidebar_sidebar (grid_id)');
        $this->addSql('DROP INDEX UNIQ_C8362FF084A0A3ED ON template_template');
        $this->addSql('ALTER TABLE template_template CHANGE content_id grid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE template_template ADD CONSTRAINT FK_C8362FF02CF16895 FOREIGN KEY (grid_id) REFERENCES grid_grid (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8362FF02CF16895 ON template_template (grid_id)');
    }
}
