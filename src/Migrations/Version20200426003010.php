<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200426003010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE smsverification ADD informer_id INT DEFAULT NULL, ADD type SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE smsverification ADD CONSTRAINT FK_DDCF5F6E6FFB3625 FOREIGN KEY (informer_id) REFERENCES informer (id)');
        $this->addSql('CREATE INDEX IDX_DDCF5F6E6FFB3625 ON smsverification (informer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE smsverification DROP FOREIGN KEY FK_DDCF5F6E6FFB3625');
        $this->addSql('DROP INDEX IDX_DDCF5F6E6FFB3625 ON smsverification');
        $this->addSql('ALTER TABLE smsverification DROP informer_id, DROP type');
    }
}
