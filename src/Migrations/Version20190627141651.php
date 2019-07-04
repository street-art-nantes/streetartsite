<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190627141651 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE fos_user ADD instagramId VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD facebookId VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD instagramAccessToken VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD facebookAccessToken VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE fos_user DROP facebookId');
        $this->addSql('ALTER TABLE fos_user DROP instagramId');
        $this->addSql('ALTER TABLE fos_user DROP facebookAccessToken');
        $this->addSql('ALTER TABLE fos_user DROP instagramAccessToken');
    }
}
