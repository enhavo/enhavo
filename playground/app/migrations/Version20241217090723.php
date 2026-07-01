<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217090723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page_page ADD revisionDate DATETIME DEFAULT NULL, ADD revisionState VARCHAR(255) DEFAULT NULL, ADD revisionParameters LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD revisionSubject_id INT DEFAULT NULL, ADD revisionUser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA7DB2BC3B FOREIGN KEY (revisionSubject_id) REFERENCES page_page (id)');
        $this->addSql('ALTER TABLE page_page ADD CONSTRAINT FK_93CEAAFA27ABAE9D FOREIGN KEY (revisionUser_id) REFERENCES user_user (id)');
        $this->addSql('CREATE INDEX IDX_93CEAAFA7DB2BC3B ON page_page (revisionSubject_id)');
        $this->addSql('CREATE INDEX IDX_93CEAAFA27ABAE9D ON page_page (revisionUser_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA7DB2BC3B');
        $this->addSql('ALTER TABLE page_page DROP FOREIGN KEY FK_93CEAAFA27ABAE9D');
        $this->addSql('DROP INDEX IDX_93CEAAFA7DB2BC3B ON page_page');
        $this->addSql('DROP INDEX IDX_93CEAAFA27ABAE9D ON page_page');
        $this->addSql('ALTER TABLE page_page DROP revisionDate, DROP revisionState, DROP revisionParameters, DROP revisionSubject_id, DROP revisionUser_id');
    }
}
