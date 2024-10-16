<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016083813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD user_email VARCHAR(160) NOT NULL, ADD user_active TINYINT(1) DEFAULT 0 NOT NULL, ADD user_unique_key VARCHAR(255) NOT NULL, ADD user_full_name VARCHAR(200) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649550872C ON user (user_email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649550872C ON user');
        $this->addSql('ALTER TABLE user DROP user_email, DROP user_active, DROP user_unique_key, DROP user_full_name');
    }
}
