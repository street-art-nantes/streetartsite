<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181026082745 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE fos_user ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE fos_user ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('UPDATE fos_user SET created_at = now(), updated_at = now ();');
        $this->addSql('ALTER TABLE fos_user ALTER COLUMN created_at SET NOT NULL;');
        $this->addSql('ALTER TABLE fos_user ALTER COLUMN updated_at SET NOT NULL;');

        $this->addSql('ALTER TABLE fos_user ADD avatar_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE fos_user DROP avatar_name');
        $this->addSql('ALTER TABLE fos_user DROP created_at');
        $this->addSql('ALTER TABLE fos_user DROP updated_at');
    }
}
