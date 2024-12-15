<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241215222203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `tbl_internship` (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, INDEX IDX_7992FA9D979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_intership_report (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `tbl_internship` ADD CONSTRAINT FK_7992FA9D979B1AD6 FOREIGN KEY (company_id) REFERENCES tbl_company (id)');
        $this->addSql('DROP TABLE tbl_intership');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbl_intership (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `tbl_internship` DROP FOREIGN KEY FK_7992FA9D979B1AD6');
        $this->addSql('DROP TABLE `tbl_internship`');
        $this->addSql('DROP TABLE tbl_intership_report');
    }
}
