<?php
require_once 'vendor/autoload.php';

if (class_exists('Dotenv\Dotenv')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
}

$credentials = parse_url(getenv('DATABASE_URL'));
$host = $credentials['host'];
$dbname = substr($credentials['path'], 1);
$user = $credentials['user'];
$password = $credentials['pass'];
require_once 'database/config.php';
