<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515175135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_gateway_config ADD gatewayType VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE payment_payment ADD token VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE payment_payment_method ADD name VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD instructions LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_gateway_config DROP gatewayType');
        $this->addSql('ALTER TABLE payment_payment DROP token');
        $this->addSql('ALTER TABLE payment_payment_method DROP name, DROP description, DROP instructions');
    }
}
