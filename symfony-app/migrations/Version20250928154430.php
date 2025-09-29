<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250928154430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create invoices table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE invoices (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', contractor_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', amount NUMERIC(10, 2) NOT NULL, number VARCHAR(100) NOT NULL, paid TINYINT(1) NOT NULL, payment_date DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6A2F2F9596901F54 (number), INDEX IDX_6A2F2F95B0265DC7 (contractor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoices ADD CONSTRAINT FK_6A2F2F95B0265DC7 FOREIGN KEY (contractor_id) REFERENCES contactors (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE invoices DROP FOREIGN KEY FK_6A2F2F95B0265DC7');
        $this->addSql('DROP TABLE invoices');
    }
}
