<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430085222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbl_internship_report (id INT AUTO_INCREMENT NOT NULL, internship_id INT NOT NULL, submission_date DATETIME NOT NULL, INDEX IDX_BAFB26E27A4A70BE (internship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tbl_internship_report ADD CONSTRAINT FK_BAFB26E27A4A70BE FOREIGN KEY (internship_id) REFERENCES tbl_internship (id)');
        $this->addSql('ALTER TABLE internship_report DROP FOREIGN KEY FK_4FAE8707A4A70BE');
        $this->addSql('DROP TABLE internship_report');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE internship_report (id INT AUTO_INCREMENT NOT NULL, internship_id INT NOT NULL, submission_date DATETIME NOT NULL, INDEX IDX_4FAE8707A4A70BE (internship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE internship_report ADD CONSTRAINT FK_4FAE8707A4A70BE FOREIGN KEY (internship_id) REFERENCES tbl_internship (id)');
        $this->addSql('ALTER TABLE tbl_internship_report DROP FOREIGN KEY FK_BAFB26E27A4A70BE');
        $this->addSql('DROP TABLE tbl_internship_report');
    }
}
