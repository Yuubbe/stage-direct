<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923094225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_school (student_id INT NOT NULL, school_id INT NOT NULL, INDEX IDX_77A8023BCB944F1A (student_id), INDEX IDX_77A8023BC32A47EE (school_id), PRIMARY KEY(student_id, school_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_school ADD CONSTRAINT FK_77A8023BCB944F1A FOREIGN KEY (student_id) REFERENCES tbl_student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_school ADD CONSTRAINT FK_77A8023BC32A47EE FOREIGN KEY (school_id) REFERENCES tbl_school (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_school DROP FOREIGN KEY FK_77A8023BCB944F1A');
        $this->addSql('ALTER TABLE student_school DROP FOREIGN KEY FK_77A8023BC32A47EE');
        $this->addSql('DROP TABLE student_school');
    }
}
