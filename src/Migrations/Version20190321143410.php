<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190321143410 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE author ADD contributor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE author ADD enabled BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C87A19A357 FOREIGN KEY (contributor_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BDAFD8C87A19A357 ON author (contributor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE author DROP CONSTRAINT FK_BDAFD8C87A19A357');
        $this->addSql('DROP INDEX IDX_BDAFD8C87A19A357');
        $this->addSql('ALTER TABLE author DROP contributor_id');
        $this->addSql('ALTER TABLE author DROP enabled');
    }
}
