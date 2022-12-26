<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221226154622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE authors (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
            name VARCHAR(30) NOT NULL, 
            PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE books (
            id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
            author_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', 
            title VARCHAR(30) NOT NULL, 
            pages SMALLINT UNSIGNED NOT NULL, 
            release_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', 
            UNIQUE INDEX UNIQ_4A1B2A92F675F31B (author_id), 
            PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92F675F31B');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE books');
    }
}
