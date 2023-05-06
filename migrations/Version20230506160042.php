<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230506160042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `posts`.`author_id` column';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE posts ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAF675F31B FOREIGN KEY (author_id) REFERENCES admin_users (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_885DBAFAF675F31B ON posts (author_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAF675F31B');
        $this->addSql('DROP INDEX IDX_885DBAFAF675F31B ON posts');
        $this->addSql('ALTER TABLE posts DROP author_id');
    }
}
