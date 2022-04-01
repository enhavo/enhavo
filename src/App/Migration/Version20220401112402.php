<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401112402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_library_file_tag DROP FOREIGN KEY FK_40C0103B12469DE2');
        $this->addSql('DROP INDEX IDX_40C0103B12469DE2 ON media_library_file_tag');
        $this->addSql('ALTER TABLE media_library_file_tag DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE media_library_file_tag CHANGE category_id term_id INT NOT NULL');
        $this->addSql('ALTER TABLE media_library_file_tag ADD CONSTRAINT FK_40C0103BE2C35FC FOREIGN KEY (term_id) REFERENCES taxonomy_term (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_40C0103BE2C35FC ON media_library_file_tag (term_id)');
        $this->addSql('ALTER TABLE media_library_file_tag ADD PRIMARY KEY (file_id, term_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX UNIQ_659142E64584665A, ADD INDEX IDX_659142E64584665A (product_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX UNIQ_659142E6EE45BDBF, ADD INDEX IDX_659142E6EE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX UNIQ_D6AFF9EA80EF684, ADD INDEX IDX_D6AFF9EA80EF684 (product_variant_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX UNIQ_D6AFF9EEE45BDBF, ADD INDEX IDX_D6AFF9EEE45BDBF (picture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_library_file_tag DROP FOREIGN KEY FK_40C0103BE2C35FC');
        $this->addSql('DROP INDEX IDX_40C0103BE2C35FC ON media_library_file_tag');
        $this->addSql('ALTER TABLE media_library_file_tag DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE media_library_file_tag CHANGE term_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE media_library_file_tag ADD CONSTRAINT FK_40C0103B12469DE2 FOREIGN KEY (category_id) REFERENCES taxonomy_term (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_40C0103B12469DE2 ON media_library_file_tag (category_id)');
        $this->addSql('ALTER TABLE media_library_file_tag ADD PRIMARY KEY (file_id, category_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX IDX_659142E64584665A, ADD UNIQUE INDEX UNIQ_659142E64584665A (product_id)');
        $this->addSql('ALTER TABLE shop_product_pictures DROP INDEX IDX_659142E6EE45BDBF, ADD UNIQUE INDEX UNIQ_659142E6EE45BDBF (picture_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX IDX_D6AFF9EA80EF684, ADD UNIQUE INDEX UNIQ_D6AFF9EA80EF684 (product_variant_id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP INDEX IDX_D6AFF9EEE45BDBF, ADD UNIQUE INDEX UNIQ_D6AFF9EEE45BDBF (picture_id)');
    }
}
