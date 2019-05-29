<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181107202947 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE author ADD website_link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE author ADD instagram_link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE author ADD avatar_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE author ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE author ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE author DROP website_link');
        $this->addSql('ALTER TABLE author DROP instagram_link');
        $this->addSql('ALTER TABLE author DROP avatar_name');
        $this->addSql('ALTER TABLE author DROP created_at');
        $this->addSql('ALTER TABLE author DROP updated_at');
    }
}
