<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211205102218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Tasks table migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE tasks(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR (255) NOT NULL,
            email VARCHAR (255) NOT NULL,
            text LONGTEXT NOT NULL,
            status TINYINT UNSIGNED NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE IF EXISTS tasks");
    }
}
