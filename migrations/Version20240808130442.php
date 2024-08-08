<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808130442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__service AS SELECT id, photo, name, description FROM service');
        $this->addSql('DROP TABLE service');
        $this->addSql('CREATE TABLE service (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, photo VARCHAR(255) DEFAULT NULL, name VARCHAR(80) NOT NULL, description VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO service (id, photo, name, description) SELECT id, photo, name, description FROM __temp__service');
        $this->addSql('DROP TABLE __temp__service');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__service AS SELECT id, photo, name, description FROM service');
        $this->addSql('DROP TABLE service');
        $this->addSql('CREATE TABLE service (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, photo VARCHAR(255) NOT NULL, name VARCHAR(80) NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO service (id, photo, name, description) SELECT id, photo, name, description FROM __temp__service');
        $this->addSql('DROP TABLE __temp__service');
    }
}
