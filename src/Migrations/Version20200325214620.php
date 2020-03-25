<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200325214620 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE patient ADD doctor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EB87F4FB17 ON patient (doctor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB87F4FB17');
        $this->addSql('DROP INDEX IDX_1ADAD7EB87F4FB17 ON patient');
        $this->addSql('ALTER TABLE patient DROP doctor_id');
    }
}
