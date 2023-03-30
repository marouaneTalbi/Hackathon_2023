<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330092246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE status_user (status_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(status_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B5957BDD6BF700BD ON status_user (status_id)');
        $this->addSql('CREATE INDEX IDX_B5957BDDA76ED395 ON status_user (user_id)');
        $this->addSql('ALTER TABLE status_user ADD CONSTRAINT FK_B5957BDD6BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_user ADD CONSTRAINT FK_B5957BDDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d6496bf700bd');
        $this->addSql('DROP INDEX idx_8d93d6496bf700bd');
        $this->addSql('ALTER TABLE "user" DROP status_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE status_user DROP CONSTRAINT FK_B5957BDD6BF700BD');
        $this->addSql('ALTER TABLE status_user DROP CONSTRAINT FK_B5957BDDA76ED395');
        $this->addSql('DROP TABLE status_user');
        $this->addSql('ALTER TABLE "user" ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d6496bf700bd FOREIGN KEY (status_id) REFERENCES status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d6496bf700bd ON "user" (status_id)');
    }
}
