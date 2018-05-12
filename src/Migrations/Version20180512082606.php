<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180512082606 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE document_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE poi_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE fos_user_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE artwork_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE author_id_seq INCREMENT BY 1');
        $this->addSql('ALTER TABLE artwork ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC576F675F31B FOREIGN KEY (author_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_881FC576F675F31B ON artwork (author_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA topology');
        $this->addSql('CREATE SCHEMA tiger');
        $this->addSql('CREATE SCHEMA tiger_data');
        $this->addSql('ALTER SEQUENCE document_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE poi_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE author_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE artwork_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE fos_user_id_seq INCREMENT BY 1');
        $this->addSql('ALTER TABLE artwork DROP CONSTRAINT FK_881FC576F675F31B');
        $this->addSql('DROP INDEX IDX_881FC576F675F31B');
        $this->addSql('ALTER TABLE artwork DROP author_id');
    }
}
