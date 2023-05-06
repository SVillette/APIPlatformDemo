<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230506115003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique constraint on admin users email';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4A95E13E7927C74 ON admin_users (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_B4A95E13E7927C74 ON admin_users');
    }
}
