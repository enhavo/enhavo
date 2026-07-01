<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211095506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person ADD revisionUser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_person ADD CONSTRAINT FK_D1DFBCF627ABAE9D FOREIGN KEY (revisionUser_id) REFERENCES user_user (id)');
        $this->addSql('CREATE INDEX IDX_D1DFBCF627ABAE9D ON app_person (revisionUser_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_person DROP FOREIGN KEY FK_D1DFBCF627ABAE9D');
        $this->addSql('DROP INDEX IDX_D1DFBCF627ABAE9D ON app_person');
        $this->addSql('ALTER TABLE app_person DROP revisionUser_id');
    }
}
