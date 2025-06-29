<?php

// Загружаем .env
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Получаем значения
$token = $_ENV['BOT_TOKEN'] ?? '';
$adminIds = array_map('trim', explode(',', $_ENV['ADMIN_IDS'] ?? ''));

$apiURL = "https://api.telegram.org/bot$token/";
