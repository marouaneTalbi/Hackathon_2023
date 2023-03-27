<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021093354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE composition_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE document_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "group_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE hackathon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE school_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE year_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE composition (id INT NOT NULL, nb_dev INT NOT NULL, nb_marketing INT NOT NULL, nb_design INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE document (id INT NOT NULL, hackathon_owner_id INT DEFAULT NULL, group_owner_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8698A7610B7EB85 ON document (hackathon_owner_id)');
        $this->addSql('CREATE INDEX IDX_D8698A76770C2D86 ON document (group_owner_id)');
        $this->addSql('CREATE TABLE "group" (id INT NOT NULL, composition_id INT DEFAULT NULL, hackathon_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6DC044C587A2E12 ON "group" (composition_id)');
        $this->addSql('CREATE INDEX IDX_6DC044C5996D90CF ON "group" (hackathon_id)');
        $this->addSql('CREATE TABLE hackathon (id INT NOT NULL, year_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, start_date DATE NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8B3AF64F40C1FEA7 ON hackathon (year_id)');
        $this->addSql('CREATE TABLE hackathon_school (hackathon_id INT NOT NULL, school_id INT NOT NULL, PRIMARY KEY(hackathon_id, school_id))');
        $this->addSql('CREATE INDEX IDX_A0EC53B1996D90CF ON hackathon_school (hackathon_id)');
        $this->addSql('CREATE INDEX IDX_A0EC53B1C32A47EE ON hackathon_school (school_id)');
        $this->addSql('CREATE TABLE school (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE year (id INT NOT NULL, date VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7610B7EB85 FOREIGN KEY (hackathon_owner_id) REFERENCES hackathon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76770C2D86 FOREIGN KEY (group_owner_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C587A2E12 FOREIGN KEY (composition_id) REFERENCES composition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD CONSTRAINT FK_6DC044C5996D90CF FOREIGN KEY (hackathon_id) REFERENCES hackathon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hackathon ADD CONSTRAINT FK_8B3AF64F40C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hackathon_school ADD CONSTRAINT FK_A0EC53B1996D90CF FOREIGN KEY (hackathon_id) REFERENCES hackathon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hackathon_school ADD CONSTRAINT FK_A0EC53B1C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE composition_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE document_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "group_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE hackathon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE school_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE year_id_seq CASCADE');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A7610B7EB85');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76770C2D86');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C587A2E12');
        $this->addSql('ALTER TABLE "group" DROP CONSTRAINT FK_6DC044C5996D90CF');
        $this->addSql('ALTER TABLE hackathon DROP CONSTRAINT FK_8B3AF64F40C1FEA7');
        $this->addSql('ALTER TABLE hackathon_school DROP CONSTRAINT FK_A0EC53B1996D90CF');
        $this->addSql('ALTER TABLE hackathon_school DROP CONSTRAINT FK_A0EC53B1C32A47EE');
        $this->addSql('DROP TABLE composition');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE hackathon');
        $this->addSql('DROP TABLE hackathon_school');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE year');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
