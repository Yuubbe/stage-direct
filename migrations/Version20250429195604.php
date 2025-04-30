<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429195604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tbl_intership_report');
        $this->addSql('ALTER TABLE tbl_company ADD CONSTRAINT FK_14EC013BDE95C867 FOREIGN KEY (sector_id) REFERENCES tbl_sector (id)');
        $this->addSql('CREATE INDEX IDX_14EC013BDE95C867 ON tbl_company (sector_id)');
        $this->addSql('ALTER TABLE tbl_internship ADD created_by_id INT DEFAULT NULL, ADD is_pending TINYINT(1) NOT NULL, ADD report_content LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbl_internship ADD CONSTRAINT FK_7992FA9DB03A8386 FOREIGN KEY (created_by_id) REFERENCES tbl_user (id)');
        $this->addSql('CREATE INDEX IDX_7992FA9DB03A8386 ON tbl_internship (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbl_intership_report (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tbl_company DROP FOREIGN KEY FK_14EC013BDE95C867');
        $this->addSql('DROP INDEX IDX_14EC013BDE95C867 ON tbl_company');
        $this->addSql('ALTER TABLE tbl_internship DROP FOREIGN KEY FK_7992FA9DB03A8386');
        $this->addSql('DROP INDEX IDX_7992FA9DB03A8386 ON tbl_internship');
        $this->addSql('ALTER TABLE tbl_internship DROP created_by_id, DROP is_pending, DROP report_content');
    }
}
