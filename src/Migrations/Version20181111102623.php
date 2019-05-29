<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181111102623 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE artwork_author (artwork_id INT NOT NULL, author_id INT NOT NULL, PRIMARY KEY(artwork_id, author_id))');
        $this->addSql('CREATE INDEX IDX_405CCBDADB8FFA4 ON artwork_author (artwork_id)');
        $this->addSql('CREATE INDEX IDX_405CCBDAF675F31B ON artwork_author (author_id)');
        $this->addSql('ALTER TABLE artwork_author ADD CONSTRAINT FK_405CCBDADB8FFA4 FOREIGN KEY (artwork_id) REFERENCES artwork (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artwork_author ADD CONSTRAINT FK_405CCBDAF675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE artwork DROP CONSTRAINT fk_881fc576f675f31b');
        $this->addSql('DROP INDEX idx_881fc576f675f31b');
        $this->addSql('ALTER TABLE artwork DROP author_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE artwork_author');
        $this->addSql('ALTER TABLE artwork ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT fk_881fc576f675f31b FOREIGN KEY (author_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_881fc576f675f31b ON artwork (author_id)');
    }
}
