<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211114174356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE url_entry_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE url_stats_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE url_entry (
          id INT NOT NULL,
          owner_id INT NOT NULL,
          real_url VARCHAR(255) NOT NULL,
          short_url VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EE85F20783360531 ON url_entry (short_url)');
        $this->addSql('CREATE INDEX IDX_EE85F2077E3C61F9 ON url_entry (owner_id)');
        $this->addSql('CREATE TABLE url_stats (
          id INT NOT NULL,
          url_id INT NOT NULL,
          device_type VARCHAR(255) NOT NULL,
          access_timestamp BIGINT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_92E308DD81CFDAE7 ON url_stats (url_id)');
        $this->addSql('CREATE TABLE "user" (
          id INT NOT NULL,
          email VARCHAR(180) NOT NULL,
          roles JSON NOT NULL,
          password VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE
          url_entry
        ADD
          CONSTRAINT FK_EE85F2077E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          url_stats
        ADD
          CONSTRAINT FK_92E308DD81CFDAE7 FOREIGN KEY (url_id) REFERENCES url_entry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE url_stats DROP CONSTRAINT FK_92E308DD81CFDAE7');
        $this->addSql('ALTER TABLE url_entry DROP CONSTRAINT FK_EE85F2077E3C61F9');
        $this->addSql('DROP SEQUENCE url_entry_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE url_stats_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE url_entry');
        $this->addSql('DROP TABLE url_stats');
        $this->addSql('DROP TABLE "user"');
    }
}
