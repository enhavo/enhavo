<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810091527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_product_variant ADD weightUnit VARCHAR(255) DEFAULT NULL, ADD volumeUnit VARCHAR(255) DEFAULT NULL, ADD lengthUnit VARCHAR(255) DEFAULT NULL, ADD overridePrice TINYINT(1) NOT NULL, ADD overrideDescription TINYINT(1) NOT NULL, ADD overrideShipping TINYINT(1) NOT NULL, ADD overrideTaxCategory TINYINT(1) NOT NULL, ADD overrideDimensions TINYINT(1) NOT NULL, CHANGE shortTitle slug VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_product_variant ADD shortTitle VARCHAR(255) DEFAULT NULL, DROP slug, DROP weightUnit, DROP volumeUnit, DROP lengthUnit, DROP overridePrice, DROP overrideDescription, DROP overrideShipping, DROP overrideTaxCategory, DROP overrideDimensions');
    }
}
