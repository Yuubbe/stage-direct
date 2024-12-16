<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216094549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tbl_company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(100) NOT NULL, tel VARCHAR(100) NOT NULL, mail VARCHAR(255) NOT NULL, zipcode VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_grade (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_home (id INT AUTO_INCREMENT NOT NULL, message LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `tbl_internship` (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, INDEX IDX_7992FA9D979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_intership_report (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_school (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, town VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_student (id INT AUTO_INCREMENT NOT NULL, grade_id INT NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, address VARCHAR(100) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, town VARCHAR(100) DEFAULT NULL, INDEX IDX_EC70A747FE19A1A8 (grade_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_school (student_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_77A8023BCB944F1A (student_id), INDEX IDX_77A8023BC32A47EE (school_id), PRIMARY KEY(student_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbl_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `tbl_internship` ADD CONSTRAINT FK_7992FA9D979B1AD6 FOREIGN KEY (company_id) REFERENCES tbl_company (id)');
        $this->addSql('ALTER TABLE tbl_student ADD CONSTRAINT FK_EC70A747FE19A1A8 FOREIGN KEY (grade_id) REFERENCES tbl_grade (id)');
        $this->addSql('ALTER TABLE student_school ADD CONSTRAINT FK_77A8023BCB944F1A FOREIGN KEY (student_id) REFERENCES tbl_student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_school ADD CONSTRAINT FK_77A8023BC32A47EE FOREIGN KEY (school_id) REFERENCES tbl_school (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `tbl_internship` DROP FOREIGN KEY FK_7992FA9D979B1AD6');
        $this->addSql('ALTER TABLE tbl_student DROP FOREIGN KEY FK_EC70A747FE19A1A8');
        $this->addSql('ALTER TABLE student_school DROP FOREIGN KEY FK_77A8023BCB944F1A');
        $this->addSql('ALTER TABLE student_school DROP FOREIGN KEY FK_77A8023BC32A47EE');
        $this->addSql('DROP TABLE tbl_company');
        $this->addSql('DROP TABLE tbl_grade');
        $this->addSql('DROP TABLE tbl_home');
        $this->addSql('DROP TABLE `tbl_internship`');
        $this->addSql('DROP TABLE tbl_intership_report');
        $this->addSql('DROP TABLE tbl_school');
        $this->addSql('DROP TABLE tbl_student');
        $this->addSql('DROP TABLE student_school');
        $this->addSql('DROP TABLE tbl_user');
    }
}
