<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427232239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_internship ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE tbl_internship ADD CONSTRAINT FK_7992FA9DB03A8386 FOREIGN KEY (created_by_id) REFERENCES tbl_user (id)');
        $this->addSql('CREATE INDEX IDX_7992FA9DB03A8386 ON tbl_internship (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `tbl_internship` DROP FOREIGN KEY FK_7992FA9DB03A8386');
        $this->addSql('DROP INDEX IDX_7992FA9DB03A8386 ON `tbl_internship`');
        $this->addSql('ALTER TABLE `tbl_internship` DROP created_by_id');
    }
}
