<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240813133606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE TABLE client_category (client_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(client_id, category_id), CONSTRAINT FK_28AA290419EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_28AA290412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_28AA290419EB6921 ON client_category (client_id)');
        $this->addSql('CREATE INDEX IDX_28AA290412469DE2 ON client_category (category_id)');
        $this->addSql('CREATE TABLE menu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, name VARCHAR(80) NOT NULL, composition VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, CONSTRAINT FK_7D053A9312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7D053A9312469DE2 ON menu (category_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__booking AS SELECT id, client_id, name, phone_number, email, nb_guest, date, special_request, is_verified FROM booking');
        $this->addSql('DROP TABLE booking');
        $this->addSql('CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, name VARCHAR(80) NOT NULL, phone_number VARCHAR(30) NOT NULL, email VARCHAR(80) NOT NULL, nb_guest INTEGER NOT NULL, date DATETIME NOT NULL, special_request VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN DEFAULT NULL, CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO booking (id, client_id, name, phone_number, email, nb_guest, date, special_request, is_verified) SELECT id, client_id, name, phone_number, email, nb_guest, date, special_request, is_verified FROM __temp__booking');
        $this->addSql('DROP TABLE __temp__booking');
        $this->addSql('CREATE INDEX IDX_E00CEDDE19EB6921 ON booking (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE client_category');
        $this->addSql('DROP TABLE menu');
        $this->addSql('CREATE TEMPORARY TABLE __temp__booking AS SELECT id, client_id, name, phone_number, email, nb_guest, date, special_request, is_verified FROM booking');
        $this->addSql('DROP TABLE booking');
        $this->addSql('CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, name VARCHAR(80) NOT NULL, phone_number VARCHAR(30) NOT NULL, email VARCHAR(80) NOT NULL, nb_guest INTEGER NOT NULL, date DATETIME NOT NULL, special_request VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO booking (id, client_id, name, phone_number, email, nb_guest, date, special_request, is_verified) SELECT id, client_id, name, phone_number, email, nb_guest, date, special_request, is_verified FROM __temp__booking');
        $this->addSql('DROP TABLE __temp__booking');
        $this->addSql('CREATE INDEX IDX_E00CEDDE19EB6921 ON booking (client_id)');
    }
}
