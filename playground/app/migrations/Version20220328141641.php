<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328141641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_product_category (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_ECA174E74584665A (product_id), INDEX IDX_ECA174E712469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_product_tag (product_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_31D1158C4584665A (product_id), INDEX IDX_31D1158CBAD26311 (tag_id), PRIMARY KEY(product_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_product_category ADD CONSTRAINT FK_ECA174E74584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_category ADD CONSTRAINT FK_ECA174E712469DE2 FOREIGN KEY (category_id) REFERENCES taxonomy_term (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_tag ADD CONSTRAINT FK_31D1158C4584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_tag ADD CONSTRAINT FK_31D1158CBAD26311 FOREIGN KEY (tag_id) REFERENCES taxonomy_term (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE shop_product_category');
        $this->addSql('DROP TABLE shop_product_tag');
    }
}
