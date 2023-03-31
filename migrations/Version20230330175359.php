<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330175359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE chat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE chat (id INT NOT NULL, client_id INT DEFAULT NULL, conversation_id INT DEFAULT NULL, text TEXT NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_659DF2AA19EB6921 ON chat (client_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA9AC0396 ON chat (conversation_id)');
        $this->addSql('CREATE TABLE conversation (id INT NOT NULL, client_id INT DEFAULT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, source VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A8E26E919EB6921 ON conversation (client_id)');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE status_user (status_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(status_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B5957BDD6BF700BD ON status_user (status_id)');
        $this->addSql('CREATE INDEX IDX_B5957BDDA76ED395 ON status_user (user_id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E919EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_user ADD CONSTRAINT FK_B5957BDD6BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_user ADD CONSTRAINT FK_B5957BDDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP last_login');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE chat_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('ALTER TABLE chat DROP CONSTRAINT FK_659DF2AA19EB6921');
        $this->addSql('ALTER TABLE chat DROP CONSTRAINT FK_659DF2AA9AC0396');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E919EB6921');
        $this->addSql('ALTER TABLE status_user DROP CONSTRAINT FK_B5957BDD6BF700BD');
        $this->addSql('ALTER TABLE status_user DROP CONSTRAINT FK_B5957BDDA76ED395');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE status_user');
        $this->addSql('ALTER TABLE "user" ADD last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }
}
