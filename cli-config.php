<?php

require_once './bootstrap/app.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;

$paths = [__DIR__ . '/app/Models'];

$config = new PhpFile(__DIR__ . '/migrations.php');

$conn = DriverManager::getConnection([
    'dbname' => env("DB_DATABASE"),
    'user' => env("DB_USERNAME"),
    'password' => env("DB_PASSWORD"),
    'host' => env("DB_HOST"),
    'driver' => 'pdo_' . env("DB_CONNECTION"),
]);

return DependencyFactory::fromConnection($config, new ExistingConnection($conn));