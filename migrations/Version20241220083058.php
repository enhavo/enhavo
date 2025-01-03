<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220083058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE block_slider_block (id INT AUTO_INCREMENT NOT NULL, node_id INT DEFAULT NULL, INDEX IDX_1474DD4F460D9FD7 (node_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_slider_block_slide (id INT AUTO_INCREMENT NOT NULL, slider_id INT DEFAULT NULL, image_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, url TINYTEXT DEFAULT NULL, position INT DEFAULT NULL, INDEX IDX_88CE3DC82CCC9638 (slider_id), INDEX IDX_88CE3DC83DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE block_slider_block ADD CONSTRAINT FK_1474DD4F460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_slider_block_slide ADD CONSTRAINT FK_88CE3DC82CCC9638 FOREIGN KEY (slider_id) REFERENCES block_slider_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_slider_block_slide ADD CONSTRAINT FK_88CE3DC83DA5256D FOREIGN KEY (image_id) REFERENCES media_file (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE block_slider_block DROP FOREIGN KEY FK_1474DD4F460D9FD7');
        $this->addSql('ALTER TABLE block_slider_block_slide DROP FOREIGN KEY FK_88CE3DC82CCC9638');
        $this->addSql('ALTER TABLE block_slider_block_slide DROP FOREIGN KEY FK_88CE3DC83DA5256D');
        $this->addSql('DROP TABLE block_slider_block');
        $this->addSql('DROP TABLE block_slider_block_slide');
    }
}
