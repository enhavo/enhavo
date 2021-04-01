<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200901091314 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE enhavo_calendar_calendar_block TO calendar_calendar_block');
        $this->addSql('RENAME TABLE enhavo_contact_contact_block TO contact_contact_block');
        $this->addSql('RENAME TABLE enhavo_newsletter_subscribe_block TO newsletter_subscribe_block');
        $this->addSql('RENAME TABLE enhavo_slider_slider_block TO slider_slider_block');
        $this->addSql('RENAME TABLE enhavo_template_resource_block TO template_resource_block');

        $this->addSql('ALTER TABLE calendar_calendar_block DROP FOREIGN KEY FK_2FAF88F9460D9FD7');
        $this->addSql('DROP INDEX idx_2faf88f9460d9fd7 ON calendar_calendar_block');
        $this->addSql('CREATE INDEX IDX_5DCDCB1C460D9FD7 ON calendar_calendar_block (node_id)');
        $this->addSql('ALTER TABLE calendar_calendar_block ADD CONSTRAINT FK_2FAF88F9460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_contact_block DROP FOREIGN KEY FK_1B10C4F2460D9FD7');
        $this->addSql('DROP INDEX idx_1b10c4f2460d9fd7 ON contact_contact_block');
        $this->addSql('CREATE INDEX IDX_8FA9C4AA460D9FD7 ON contact_contact_block (node_id)');
        $this->addSql('ALTER TABLE contact_contact_block ADD CONSTRAINT FK_1B10C4F2460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_subscribe_block DROP FOREIGN KEY FK_3AE60C5C460D9FD7');
        $this->addSql('DROP INDEX idx_3ae60c5c460d9fd7 ON newsletter_subscribe_block');
        $this->addSql('CREATE INDEX IDX_ED2702D7460D9FD7 ON newsletter_subscribe_block (node_id)');
        $this->addSql('ALTER TABLE newsletter_subscribe_block ADD CONSTRAINT FK_3AE60C5C460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slider_slider_block DROP FOREIGN KEY FK_19197EFB460D9FD7');
        $this->addSql('DROP INDEX idx_19197efb460d9fd7 ON slider_slider_block');
        $this->addSql('CREATE INDEX IDX_2FB93E94460D9FD7 ON slider_slider_block (node_id)');
        $this->addSql('ALTER TABLE slider_slider_block ADD CONSTRAINT FK_19197EFB460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_resource_block DROP FOREIGN KEY FK_7FD350EF460D9FD7');
        $this->addSql('DROP INDEX idx_7fd350ef460d9fd7 ON template_resource_block');
        $this->addSql('CREATE INDEX IDX_DB1130A460D9FD7 ON template_resource_block (node_id)');
        $this->addSql('ALTER TABLE template_resource_block ADD CONSTRAINT FK_7FD350EF460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE calendar_calendar_block DROP FOREIGN KEY FK_5DCDCB1C460D9FD7');
        $this->addSql('DROP INDEX idx_5dcdcb1c460d9fd7 ON calendar_calendar_block');
        $this->addSql('CREATE INDEX IDX_2FAF88F9460D9FD7 ON calendar_calendar_block (node_id)');
        $this->addSql('ALTER TABLE calendar_calendar_block ADD CONSTRAINT FK_5DCDCB1C460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_contact_block DROP FOREIGN KEY FK_8FA9C4AA460D9FD7');
        $this->addSql('DROP INDEX idx_8fa9c4aa460d9fd7 ON contact_contact_block');
        $this->addSql('CREATE INDEX IDX_1B10C4F2460D9FD7 ON contact_contact_block (node_id)');
        $this->addSql('ALTER TABLE contact_contact_block ADD CONSTRAINT FK_8FA9C4AA460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE newsletter_subscribe_block DROP FOREIGN KEY FK_ED2702D7460D9FD7');
        $this->addSql('DROP INDEX idx_ed2702d7460d9fd7 ON newsletter_subscribe_block');
        $this->addSql('CREATE INDEX IDX_3AE60C5C460D9FD7 ON newsletter_subscribe_block (node_id)');
        $this->addSql('ALTER TABLE newsletter_subscribe_block ADD CONSTRAINT FK_ED2702D7460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slider_slider_block DROP FOREIGN KEY FK_2FB93E94460D9FD7');
        $this->addSql('DROP INDEX idx_2fb93e94460d9fd7 ON slider_slider_block');
        $this->addSql('CREATE INDEX IDX_19197EFB460D9FD7 ON slider_slider_block (node_id)');
        $this->addSql('ALTER TABLE slider_slider_block ADD CONSTRAINT FK_2FB93E94460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE template_resource_block DROP FOREIGN KEY FK_DB1130A460D9FD7');
        $this->addSql('DROP INDEX idx_db1130a460d9fd7 ON template_resource_block');
        $this->addSql('CREATE INDEX IDX_7FD350EF460D9FD7 ON template_resource_block (node_id)');
        $this->addSql('ALTER TABLE template_resource_block ADD CONSTRAINT FK_DB1130A460D9FD7 FOREIGN KEY (node_id) REFERENCES block_node (id) ON DELETE CASCADE');

        $this->addSql('RENAME TABLE calendar_calendar_block TO enhavo_calendar_calendar_block');
        $this->addSql('RENAME TABLE contact_contact_block TO enhavo_contact_contact_block');
        $this->addSql('RENAME TABLE newsletter_subscribe_block TO enhavo_newsletter_subscribe_block');
        $this->addSql('RENAME TABLE slider_slider_block TO enhavo_slider_slider_block');
        $this->addSql('RENAME TABLE template_resource_block TO enhavo_template_resource_block');
    }
}
