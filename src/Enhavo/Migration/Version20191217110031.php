<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191217110031 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE newsletter_newsletter_group (newsletter_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_35E6041022DB1917 (newsletter_id), INDEX IDX_35E60410FE54D947 (group_id), PRIMARY KEY(newsletter_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE newsletter_newsletter_group ADD CONSTRAINT FK_35E6041022DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter_newsletter (id)');
        $this->addSql('ALTER TABLE newsletter_newsletter_group ADD CONSTRAINT FK_35E60410FE54D947 FOREIGN KEY (group_id) REFERENCES newsletter_group (id)');
        $this->addSql('ALTER TABLE newsletter_newsletter DROP FOREIGN KEY FK_9390BC62FE54D947');
        $this->addSql('DROP INDEX IDX_9390BC62FE54D947 ON newsletter_newsletter');
        $this->addSql('ALTER TABLE newsletter_newsletter DROP group_id, DROP title');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE newsletter_newsletter_group');
        $this->addSql('ALTER TABLE newsletter_newsletter ADD group_id INT DEFAULT NULL, ADD title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE newsletter_newsletter ADD CONSTRAINT FK_9390BC62FE54D947 FOREIGN KEY (group_id) REFERENCES newsletter_group (id)');
        $this->addSql('CREATE INDEX IDX_9390BC62FE54D947 ON newsletter_newsletter (group_id)');
    }
}
