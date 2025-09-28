<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250928154724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create warnings table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE warnings (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', object_type VARCHAR(100) NOT NULL, type VARCHAR(100) NOT NULL, object_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE warnings');
    }
}
