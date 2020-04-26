<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200426003959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sms_verification (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, informer_id INT DEFAULT NULL, phone_number INT NOT NULL, pin_code INT NOT NULL, status SMALLINT NOT NULL, type SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3DF50CEF6B899279 (patient_id), INDEX IDX_3DF50CEF6FFB3625 (informer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sms_verification ADD CONSTRAINT FK_3DF50CEF6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE sms_verification ADD CONSTRAINT FK_3DF50CEF6FFB3625 FOREIGN KEY (informer_id) REFERENCES informer (id)');
        $this->addSql('DROP TABLE smsverification');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE smsverification (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, informer_id INT DEFAULT NULL, phone_number INT NOT NULL, pin_code INT NOT NULL, status SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type SMALLINT NOT NULL, INDEX IDX_DDCF5F6E6B899279 (patient_id), INDEX IDX_DDCF5F6E6FFB3625 (informer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE smsverification ADD CONSTRAINT FK_DDCF5F6E6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE smsverification ADD CONSTRAINT FK_DDCF5F6E6FFB3625 FOREIGN KEY (informer_id) REFERENCES informer (id)');
        $this->addSql('DROP TABLE sms_verification');
    }
}
