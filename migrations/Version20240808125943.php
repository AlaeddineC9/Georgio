<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808125943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__galerie AS SELECT id, photo, description FROM galerie');
        $this->addSql('DROP TABLE galerie');
        $this->addSql('CREATE TABLE galerie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, photo VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO galerie (id, photo, description) SELECT id, photo, description FROM __temp__galerie');
        $this->addSql('DROP TABLE __temp__galerie');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__galerie AS SELECT id, photo, description FROM galerie');
        $this->addSql('DROP TABLE galerie');
        $this->addSql('CREATE TABLE galerie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, photo VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO galerie (id, photo, description) SELECT id, photo, description FROM __temp__galerie');
        $this->addSql('DROP TABLE __temp__galerie');
    }
}
