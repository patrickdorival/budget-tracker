<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125135110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__transactions AS SELECT id, amount, description, created_at FROM transactions');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('CREATE TABLE transactions (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , transaction_category_id INTEGER DEFAULT NULL, amount INTEGER NOT NULL, description VARCHAR(255) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_EAA81A4CAECF88CF FOREIGN KEY (transaction_category_id) REFERENCES transaction_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO transactions (id, amount, description, created_at) SELECT id, amount, description, created_at FROM __temp__transactions');
        $this->addSql('DROP TABLE __temp__transactions');
        $this->addSql('CREATE INDEX IDX_EAA81A4CAECF88CF ON transactions (transaction_category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE transaction_category');
        $this->addSql('DROP INDEX IDX_EAA81A4CAECF88CF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__transactions AS SELECT id, amount, description, created_at FROM transactions');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('CREATE TABLE transactions (id CHAR(36) NOT NULL --(DC2Type:guid)
        , amount INTEGER NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO transactions (id, amount, description, created_at) SELECT id, amount, description, created_at FROM __temp__transactions');
        $this->addSql('DROP TABLE __temp__transactions');
    }
}
