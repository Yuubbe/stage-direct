<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429070345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_internship DROP FOREIGN KEY FK_7992FA9D4BD2A4C0');
        $this->addSql('CREATE TABLE tbl_internship_report (id INT AUTO_INCREMENT NOT NULL, internship_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_BAFB26E27A4A70BE (internship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tbl_internship_report ADD CONSTRAINT FK_BAFB26E27A4A70BE FOREIGN KEY (internship_id) REFERENCES tbl_internship (id)');
        $this->addSql('DROP TABLE tbl_intership_report');
        $this->addSql('DROP INDEX UNIQ_7992FA9D4BD2A4C0 ON tbl_internship');
        $this->addSql('ALTER TABLE tbl_internship DROP report_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbl_intership_report (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tbl_internship_report DROP FOREIGN KEY FK_BAFB26E27A4A70BE');
        $this->addSql('DROP TABLE tbl_internship_report');
        $this->addSql('ALTER TABLE tbl_internship ADD report_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbl_internship ADD CONSTRAINT FK_7992FA9D4BD2A4C0 FOREIGN KEY (report_id) REFERENCES tbl_intership_report (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7992FA9D4BD2A4C0 ON tbl_internship (report_id)');
    }
}
