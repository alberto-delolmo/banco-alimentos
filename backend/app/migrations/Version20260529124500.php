<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260529124500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename app_user primary key constraint';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DO $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM pg_constraint WHERE conname = 'user_pkey'
    ) AND NOT EXISTS (
        SELECT 1 FROM pg_constraint WHERE conname = 'app_user_pkey'
    ) THEN
        ALTER TABLE app_user RENAME CONSTRAINT user_pkey TO app_user_pkey;
    END IF;
END
$$
SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DO $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM pg_constraint WHERE conname = 'app_user_pkey'
    ) AND NOT EXISTS (
        SELECT 1 FROM pg_constraint WHERE conname = 'user_pkey'
    ) THEN
        ALTER TABLE app_user RENAME CONSTRAINT app_user_pkey TO user_pkey;
    END IF;
END
$$
SQL);
    }
}
