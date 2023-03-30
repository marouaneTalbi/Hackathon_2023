<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330131133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE content_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE template_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE status_user (status_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(status_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B5957BDD6BF700BD ON status_user (status_id)');
        $this->addSql('CREATE INDEX IDX_B5957BDDA76ED395 ON status_user (user_id)');
        $this->addSql('CREATE TABLE template (id INT NOT NULL, image VARCHAR(255) DEFAULT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE status_user ADD CONSTRAINT FK_B5957BDD6BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_user ADD CONSTRAINT FK_B5957BDDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT fk_6a2ca10c84a0a3ed');
        $this->addSql('ALTER TABLE content_tag DROP CONSTRAINT fk_b662e17684a0a3ed');
        $this->addSql('ALTER TABLE content_tag DROP CONSTRAINT fk_b662e176bad26311');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE content_tag');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE template_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE content_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE content (id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, tags VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN content.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN content.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, content_id INT NOT NULL, type_media VARCHAR(255) DEFAULT NULL, media_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6a2ca10c84a0a3ed ON media (content_id)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE content_tag (content_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(content_id, tag_id))');
        $this->addSql('CREATE INDEX idx_b662e176bad26311 ON content_tag (tag_id)');
        $this->addSql('CREATE INDEX idx_b662e17684a0a3ed ON content_tag (content_id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT fk_6a2ca10c84a0a3ed FOREIGN KEY (content_id) REFERENCES content (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE content_tag ADD CONSTRAINT fk_b662e17684a0a3ed FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE content_tag ADD CONSTRAINT fk_b662e176bad26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_user DROP CONSTRAINT FK_B5957BDD6BF700BD');
        $this->addSql('ALTER TABLE status_user DROP CONSTRAINT FK_B5957BDDA76ED395');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE status_user');
        $this->addSql('DROP TABLE template');
    }
}
