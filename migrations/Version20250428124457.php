<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428124457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_school DROP FOREIGN KEY FK_77A8023BC32A47EE');
        $this->addSql('ALTER TABLE student_school DROP FOREIGN KEY FK_77A8023BCB944F1A');
        $this->addSql('ALTER TABLE tbl_student DROP FOREIGN KEY FK_EC70A747FE19A1A8');
        $this->addSql('DROP TABLE student_school');
        $this->addSql('DROP TABLE tbl_student');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_school (student_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_77A8023BCB944F1A (student_id), INDEX IDX_77A8023BC32A47EE (school_id), PRIMARY KEY(student_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tbl_student (id INT AUTO_INCREMENT NOT NULL, grade_id INT NOT NULL, firstname VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, lastname VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, address VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, zipcode VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, town VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_EC70A747FE19A1A8 (grade_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE student_school ADD CONSTRAINT FK_77A8023BC32A47EE FOREIGN KEY (school_id) REFERENCES tbl_school (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_school ADD CONSTRAINT FK_77A8023BCB944F1A FOREIGN KEY (student_id) REFERENCES tbl_student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tbl_student ADD CONSTRAINT FK_EC70A747FE19A1A8 FOREIGN KEY (grade_id) REFERENCES tbl_grade (id)');
        $this->addSql('DROP TABLE contact');
    }
}
