<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819084447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__booking AS SELECT id, client_id, name, phone_number, email, nb_guest, is_accepted, can_book, date, special_request, isverified FROM booking');
        $this->addSql('DROP TABLE booking');
        $this->addSql('CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, name VARCHAR(80) NOT NULL, phone_number VARCHAR(30) NOT NULL, email VARCHAR(80) NOT NULL, nb_guest INTEGER NOT NULL, is_accepted BOOLEAN DEFAULT NULL, can_book BOOLEAN NOT NULL, date DATETIME NOT NULL, special_request VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN DEFAULT NULL, CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO booking (id, client_id, name, phone_number, email, nb_guest, is_accepted, can_book, date, special_request, is_verified) SELECT id, client_id, name, phone_number, email, nb_guest, is_accepted, can_book, date, special_request, isverified FROM __temp__booking');
        $this->addSql('DROP TABLE __temp__booking');
        $this->addSql('CREATE INDEX IDX_E00CEDDE19EB6921 ON booking (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__booking AS SELECT id, client_id, name, phone_number, email, nb_guest, is_accepted, can_book, date, special_request, is_verified FROM booking');
        $this->addSql('DROP TABLE booking');
        $this->addSql('CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, name VARCHAR(80) NOT NULL, phone_number VARCHAR(30) NOT NULL, email VARCHAR(80) NOT NULL, nb_guest INTEGER NOT NULL, is_accepted BOOLEAN DEFAULT NULL, can_book BOOLEAN NOT NULL, date DATETIME NOT NULL, special_request VARCHAR(255) DEFAULT NULL, isverified BOOLEAN DEFAULT NULL, CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO booking (id, client_id, name, phone_number, email, nb_guest, is_accepted, can_book, date, special_request, isverified) SELECT id, client_id, name, phone_number, email, nb_guest, is_accepted, can_book, date, special_request, is_verified FROM __temp__booking');
        $this->addSql('DROP TABLE __temp__booking');
        $this->addSql('CREATE INDEX IDX_E00CEDDE19EB6921 ON booking (client_id)');
    }
}
