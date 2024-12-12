<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212114147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_student ADD grade_id INT NOT NULL');
        $this->addSql('ALTER TABLE tbl_student ADD CONSTRAINT FK_EC70A747FE19A1A8 FOREIGN KEY (grade_id) REFERENCES tbl_grade (id)');
        $this->addSql('CREATE INDEX IDX_EC70A747FE19A1A8 ON tbl_student (grade_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_student DROP FOREIGN KEY FK_EC70A747FE19A1A8');
        $this->addSql('DROP INDEX IDX_EC70A747FE19A1A8 ON tbl_student');
        $this->addSql('ALTER TABLE tbl_student DROP grade_id');
    }
}
