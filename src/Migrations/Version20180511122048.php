<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180511122048 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE document DROP CONSTRAINT fk_d8698a7688194de8');
        $this->addSql('ALTER SEQUENCE document_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE poi_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE author_id_seq INCREMENT BY 1');
        $this->addSql('DROP SEQUENCE oeuvre_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE artwork_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE artwork (id INT NOT NULL, poi_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, tags TEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_881FC5767EACE855 ON artwork (poi_id)');
        $this->addSql('COMMENT ON COLUMN artwork.tags IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC5767EACE855 FOREIGN KEY (poi_id) REFERENCES poi (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE oeuvre');
        $this->addSql('DROP INDEX idx_d8698a7688194de8');
        $this->addSql('ALTER TABLE document RENAME COLUMN oeuvre_id TO artwork_id');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76DB8FFA4 FOREIGN KEY (artwork_id) REFERENCES artwork (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D8698A76DB8FFA4 ON document (artwork_id)');
        $this->addSql('ALTER TABLE poi ADD latitude NUMERIC(8, 6) DEFAULT NULL');
        $this->addSql('ALTER TABLE poi ADD longitude NUMERIC(9, 6) DEFAULT NULL');
        $this->addSql('ALTER TABLE poi ADD point geometry(POINT, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE poi DROP coordinates');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA topology');
        $this->addSql('CREATE SCHEMA tiger');
        $this->addSql('CREATE SCHEMA tiger_data');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76DB8FFA4');
        $this->addSql('ALTER SEQUENCE author_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE document_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE poi_id_seq INCREMENT BY 1');
        $this->addSql('DROP SEQUENCE artwork_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE oeuvre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE oeuvre (id INT NOT NULL, poi_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ended_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, tags TEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_35fe2efe7eace855 ON oeuvre (poi_id)');
        $this->addSql('COMMENT ON COLUMN oeuvre.tags IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE oeuvre ADD CONSTRAINT fk_35fe2efe7eace855 FOREIGN KEY (poi_id) REFERENCES poi (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE artwork');
        $this->addSql('DROP INDEX IDX_D8698A76DB8FFA4');
        $this->addSql('ALTER TABLE document RENAME COLUMN artwork_id TO oeuvre_id');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT fk_d8698a7688194de8 FOREIGN KEY (oeuvre_id) REFERENCES oeuvre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d8698a7688194de8 ON document (oeuvre_id)');
        $this->addSql('ALTER TABLE poi ADD coordinates geometry(POINT, 0) NOT NULL');
        $this->addSql('ALTER TABLE poi DROP latitude');
        $this->addSql('ALTER TABLE poi DROP longitude');
        $this->addSql('ALTER TABLE poi DROP point');
        $this->addSql('COMMENT ON COLUMN poi.coordinates IS \'(DC2Type:point)\'');
    }
}
