<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260529123000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename user table to app_user and seed admin user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DO $$
BEGIN
    IF to_regclass('public.app_user') IS NULL AND to_regclass('public."user"') IS NOT NULL THEN
        ALTER TABLE "user" RENAME TO app_user;
    END IF;
END
$$
SQL);

        $this->addSql(<<<'SQL'
INSERT INTO app_user (id, name, first_surname, second_surname, email, password, roles)
VALUES (
    '550e8400-e29b-41d4-a716-446655440001',
    'Admin',
    'Banco',
    NULL,
    'admin@bancoalimentos.local',
    '$2y$13$BuZQO3c34PLXTtA9ljvRde4CfE13NIU9UEhRuejurImq6ciKq2Yki',
    '["ROLE_ADMIN"]'
)
ON CONFLICT (id) DO UPDATE SET
    name = EXCLUDED.name,
    first_surname = EXCLUDED.first_surname,
    second_surname = EXCLUDED.second_surname,
    email = EXCLUDED.email,
    password = EXCLUDED.password,
    roles = EXCLUDED.roles
SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM app_user WHERE email = 'admin@bancoalimentos.local'");

        $this->addSql(<<<'SQL'
DO $$
BEGIN
    IF to_regclass('public."user"') IS NULL AND to_regclass('public.app_user') IS NOT NULL THEN
        ALTER TABLE app_user RENAME TO "user";
    END IF;
END
$$
SQL);
    }
}
