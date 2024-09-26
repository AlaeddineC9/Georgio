<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240904092532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate contact table and remove user field.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__contact AS SELECT id, client_id, name, email, phone_number, message, isRead FROM contact');
        $this->addSql('DROP TABLE contact');
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, name VARCHAR(80) NOT NULL, email VARCHAR(80) NOT NULL, phone_number VARCHAR(30) NOT NULL, message VARCHAR(255) NOT NULL, isRead BOOLEAN NOT NULL, CONSTRAINT FK_4C62E63819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO contact (id, client_id, name, email, phone_number, message, isRead) SELECT id, client_id, name, email, phone_number, message, isRead FROM __temp__contact');
        $this->addSql('DROP TABLE __temp__contact');
        $this->addSql('CREATE INDEX IDX_4C62E63819EB6921 ON contact (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__contact AS SELECT id, client_id, name, email, phone_number, message, isRead FROM contact');
        $this->addSql('DROP TABLE contact');
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER DEFAULT NULL, name VARCHAR(80) NOT NULL, email VARCHAR(80) NOT NULL, phone_number VARCHAR(30) NOT NULL, message VARCHAR(255) NOT NULL, isRead BOOLEAN DEFAULT NULL, CONSTRAINT FK_4C62E63819EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO contact (id, client_id, name, email, phone_number, message, isRead) SELECT id, client_id, name, email, phone_number, message, isRead FROM __temp__contact');
        $this->addSql('DROP TABLE __temp__contact');
        $this->addSql('CREATE INDEX IDX_4C62E63819EB6921 ON contact (client_id)');
    }
}