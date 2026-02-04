<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260106061817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person CHANGE revisionParameters revisionParameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE app_test_block CHANGE directions directions JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE media_format CHANGE parameters parameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE newsletter_receiver CHANGE parameters parameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE page_page CHANGE revisionParameters revisionParameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE routing_route CHANGE schemes schemes JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE methods methods JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE defaults defaults JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE requirements requirements JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE options options JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page_page CHANGE revisionParameters revisionParameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE app_test_block CHANGE directions directions JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE newsletter_receiver CHANGE parameters parameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE routing_route CHANGE schemes schemes JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE methods methods JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE defaults defaults JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE requirements requirements JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE options options JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE media_format CHANGE parameters parameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE app_person CHANGE revisionParameters revisionParameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }
}
