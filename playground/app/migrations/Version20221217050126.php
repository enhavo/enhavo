<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221217050126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_product_pictures DROP FOREIGN KEY FK_659142E64584665A');
        $this->addSql('ALTER TABLE shop_product_pictures DROP FOREIGN KEY FK_659142E6EE45BDBF');
        $this->addSql('ALTER TABLE shop_product_pictures ADD CONSTRAINT FK_659142E64584665A FOREIGN KEY (product_id) REFERENCES shop_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_pictures ADD CONSTRAINT FK_659142E6EE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_variant ADD overridePictures TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP FOREIGN KEY FK_D6AFF9EA80EF684');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP FOREIGN KEY FK_D6AFF9EEE45BDBF');
        $this->addSql('ALTER TABLE shop_product_variant_pictures ADD CONSTRAINT FK_D6AFF9EA80EF684 FOREIGN KEY (product_variant_id) REFERENCES shop_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product_variant_pictures ADD CONSTRAINT FK_D6AFF9EEE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_product_pictures DROP FOREIGN KEY FK_659142E64584665A');
        $this->addSql('ALTER TABLE shop_product_pictures DROP FOREIGN KEY FK_659142E6EE45BDBF');
        $this->addSql('ALTER TABLE shop_product_pictures ADD CONSTRAINT FK_659142E64584665A FOREIGN KEY (product_id) REFERENCES shop_product (id)');
        $this->addSql('ALTER TABLE shop_product_pictures ADD CONSTRAINT FK_659142E6EE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE shop_product_variant DROP overridePictures');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP FOREIGN KEY FK_D6AFF9EA80EF684');
        $this->addSql('ALTER TABLE shop_product_variant_pictures DROP FOREIGN KEY FK_D6AFF9EEE45BDBF');
        $this->addSql('ALTER TABLE shop_product_variant_pictures ADD CONSTRAINT FK_D6AFF9EA80EF684 FOREIGN KEY (product_variant_id) REFERENCES shop_product_variant (id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures ADD CONSTRAINT FK_D6AFF9EEE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id)');
    }
}
