<?php

declare(strict_types=1);

namespace App\Migrations;

use App\Models\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211205111908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Users table migrations';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE users(
            id INT (6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            login VARCHAR (255) UNIQUE NOT NULL,
            password VARCHAR (255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");

        $hashedPassword = User::generatePassword('123');

        $this->addSql("INSERT INTO users (login, password) VALUES (\"admin\", \"$hashedPassword\")");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE IF EXISTS users");
    }
}
