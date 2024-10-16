<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs! 😎
 */
final class Version20241016084825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }
    // YEEEAHHH 😍
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD post_slug VARCHAR(162) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D51C8FC69 ON post (post_slug)');
    }
    // NOOOO 😭
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5A8A6C8D51C8FC69 ON post');
        $this->addSql('ALTER TABLE post DROP post_slug');
    }
}
