<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428125208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_company ADD street VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(100) NOT NULL, ADD country VARCHAR(100) DEFAULT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD fax VARCHAR(20) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD company_size VARCHAR(7) DEFAULT NULL, ADD sector_id INT DEFAULT NULL, DROP address, DROP tel, DROP mail, CHANGE zipcode zipcode VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_company ADD tel VARCHAR(100) NOT NULL, ADD mail VARCHAR(255) NOT NULL, DROP street, DROP country, DROP phone, DROP fax, DROP email, DROP company_size, DROP sector_id, CHANGE zipcode zipcode VARCHAR(25) NOT NULL, CHANGE city address VARCHAR(100) NOT NULL');
    }
}
