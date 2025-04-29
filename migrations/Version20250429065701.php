<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429065701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_internship ADD report_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbl_internship ADD CONSTRAINT FK_7992FA9D4BD2A4C0 FOREIGN KEY (report_id) REFERENCES tbl_intership_report (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7992FA9D4BD2A4C0 ON tbl_internship (report_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_internship DROP FOREIGN KEY FK_7992FA9D4BD2A4C0');
        $this->addSql('DROP INDEX UNIQ_7992FA9D4BD2A4C0 ON tbl_internship');
        $this->addSql('ALTER TABLE tbl_internship DROP report_id');
    }
}
