<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131010356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD134ECB4E6');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD134ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460F34ECB4E6');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460F34ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA34ECB4E6');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA34ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE translation_translation_route DROP FOREIGN KEY FK_FDABACE634ECB4E6');
        $this->addSql('ALTER TABLE translation_translation_route ADD CONSTRAINT FK_FDABACE634ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_article DROP FOREIGN KEY FK_EFE84AD134ECB4E6');
        $this->addSql('ALTER TABLE article_article ADD CONSTRAINT FK_EFE84AD134ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar_appointment DROP FOREIGN KEY FK_8EC4460F34ECB4E6');
        $this->addSql('ALTER TABLE calendar_appointment ADD CONSTRAINT FK_8EC4460F34ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA34ECB4E6');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA34ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE translation_translation_route DROP FOREIGN KEY FK_FDABACE634ECB4E6');
        $this->addSql('ALTER TABLE translation_translation_route ADD CONSTRAINT FK_FDABACE634ECB4E6 FOREIGN KEY (route_id) REFERENCES routing_route (id)');
    }
}
