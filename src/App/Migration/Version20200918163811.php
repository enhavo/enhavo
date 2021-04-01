<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200918163811 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shop_product_variant_pictures (product_variant_id INT NOT NULL, picture_id INT NOT NULL, UNIQUE INDEX UNIQ_D6AFF9EA80EF684 (product_variant_id), UNIQUE INDEX UNIQ_D6AFF9EEE45BDBF (picture_id), PRIMARY KEY(product_variant_id, picture_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_product_variant_pictures ADD CONSTRAINT FK_D6AFF9EA80EF684 FOREIGN KEY (product_variant_id) REFERENCES shop_product_variant (id)');
        $this->addSql('ALTER TABLE shop_product_variant_pictures ADD CONSTRAINT FK_D6AFF9EEE45BDBF FOREIGN KEY (picture_id) REFERENCES media_file (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE shop_product_variant_pictures');
    }
}
