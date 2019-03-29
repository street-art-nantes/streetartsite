<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180512130320 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE document (id SERIAL NOT NULL, artwork_id INT DEFAULT NULL, file VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8698A76DB8FFA4 ON document (artwork_id)');
        $this->addSql('CREATE TABLE poi (id SERIAL NOT NULL, latitude NUMERIC(8, 6) DEFAULT NULL, longitude NUMERIC(9, 6) DEFAULT NULL, point geometry(POINT, 0) DEFAULT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fos_user (id SERIAL NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479C05FB297 ON fos_user (confirmation_token)');
        $this->addSql('COMMENT ON COLUMN fos_user.roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE artwork (id SERIAL NOT NULL, poi_id INT DEFAULT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, tags TEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_881FC5767EACE855 ON artwork (poi_id)');
        $this->addSql('CREATE INDEX IDX_881FC576F675F31B ON artwork (author_id)');
        $this->addSql('COMMENT ON COLUMN artwork.tags IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE author (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, biography TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76DB8FFA4 FOREIGN KEY (artwork_id) REFERENCES artwork (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC5767EACE855 FOREIGN KEY (poi_id) REFERENCES poi (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC576F675F31B FOREIGN KEY (author_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA topology');
        $this->addSql('ALTER TABLE artwork DROP CONSTRAINT FK_881FC5767EACE855');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76DB8FFA4');
        $this->addSql('ALTER TABLE artwork DROP CONSTRAINT FK_881FC576F675F31B');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE poi');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE artwork');
        $this->addSql('DROP TABLE author');
    }
}
