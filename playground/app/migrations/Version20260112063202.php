<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112063202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person CHANGE revisionParameters revisionParameters JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE app_test_block CHANGE directions directions JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE media_file CHANGE parameters parameters JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE media_format CHANGE parameters parameters JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter_pending_subscriber CHANGE data data JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE newsletter_receiver CHANGE parameters parameters JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE page_page CHANGE revisionParameters revisionParameters JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE routing_route CHANGE schemes schemes JSON DEFAULT NULL, CHANGE methods methods JSON DEFAULT NULL, CHANGE defaults defaults JSON DEFAULT NULL, CHANGE requirements requirements JSON DEFAULT NULL, CHANGE options options JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE user_group CHANGE roles roles JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE user_user CHANGE roles roles JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person CHANGE revisionParameters revisionParameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE app_test_block CHANGE directions directions JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE media_file CHANGE parameters parameters LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE media_format CHANGE parameters parameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE newsletter_pending_subscriber CHANGE data data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\'');
        $this->addSql('ALTER TABLE newsletter_receiver CHANGE parameters parameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE page_page CHANGE revisionParameters revisionParameters JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE routing_route CHANGE schemes schemes JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE methods methods JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE defaults defaults JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE requirements requirements JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE options options JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user_group CHANGE roles roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE user_user CHANGE roles roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
