<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230505193817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `posts` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, published_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_885DBAFAC288C859 (ulid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE posts');
    }
}
