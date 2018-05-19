<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180519195304 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE document ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE document ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE artwork ADD contributor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE artwork ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC5767A19A357 FOREIGN KEY (contributor_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_881FC5767A19A357 ON artwork (contributor_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA topology');
        $this->addSql('ALTER TABLE artwork DROP CONSTRAINT FK_881FC5767A19A357');
        $this->addSql('DROP INDEX IDX_881FC5767A19A357');
        $this->addSql('ALTER TABLE artwork DROP contributor_id');
        $this->addSql('ALTER TABLE artwork DROP updated_at');
        $this->addSql('ALTER TABLE document DROP created_at');
        $this->addSql('ALTER TABLE document DROP updated_at');
    }
}
