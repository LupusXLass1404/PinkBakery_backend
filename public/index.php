<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// 載入 composer 套件
require __DIR__ . '/../vendor/autoload.php';


$app = AppFactory::create();

// 解析JSON
$app->addBodyParsingMiddleware();

// 顯示錯誤訊息（開發用）
$app->addRoutingMiddleware();

// 讀取 .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$env = $_ENV['APP_ENV'] ?: 'development';

if ($env === 'development') {
    $app->setBasePath($_ENV['LOCAL_BASE_PATH']);
} else {
    $app->setBasePath($_ENV['PROD_BASE_PATH']);
}

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// routes
(require __DIR__ . '/../src/routes/api.php')($app);

// Run app
$app->run();