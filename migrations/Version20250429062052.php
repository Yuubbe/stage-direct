<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429062052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbl_company (id INT AUTO_INCREMENT NOT NULL, sector_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, fax VARCHAR(20) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, company_size VARCHAR(7) DEFAULT NULL, INDEX IDX_14EC013BDE95C867 (sector_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_grade (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_home (id INT AUTO_INCREMENT NOT NULL, message LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_internship (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, INDEX IDX_7992FA9D979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_intership_report (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_school (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, town VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_sector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tbl_company ADD CONSTRAINT FK_14EC013BDE95C867 FOREIGN KEY (sector_id) REFERENCES tbl_sector (id)');
        $this->addSql('ALTER TABLE tbl_internship ADD CONSTRAINT FK_7992FA9D979B1AD6 FOREIGN KEY (company_id) REFERENCES tbl_company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_company DROP FOREIGN KEY FK_14EC013BDE95C867');
        $this->addSql('ALTER TABLE tbl_internship DROP FOREIGN KEY FK_7992FA9D979B1AD6');
        $this->addSql('DROP TABLE tbl_company');
        $this->addSql('DROP TABLE tbl_contact');
        $this->addSql('DROP TABLE tbl_grade');
        $this->addSql('DROP TABLE tbl_home');
        $this->addSql('DROP TABLE tbl_internship');
        $this->addSql('DROP TABLE tbl_intership_report');
        $this->addSql('DROP TABLE tbl_school');
        $this->addSql('DROP TABLE tbl_sector');
        $this->addSql('DROP TABLE tbl_user');
    }
}
