<?php declare(strict_types=1);

require __DIR__ . '../../vendor/autoload.php';

$settings = require 'config/settings.php';

$baseDir = __DIR__ . '/../';
$envFile = $baseDir . '.env';
if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::create($baseDir);
    $dotenv->load();
}

require __DIR__ . '/helpers/global.php';
require 'initializer.php';
require 'controllers.php';
require __DIR__ . '/middleware/AuthMiddleware.class.php';
require __DIR__ . '/routes/web.php';
