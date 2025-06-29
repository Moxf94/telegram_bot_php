<?php

$token = '8087643659:AAFgaLeU2E8tZbazOaM150DxVEX0LsE9tfg';
$apiURL = "https://api.telegram.org/bot'.$token/";
$adminId = 346132672;

$update = json_decode(file_get_contents("php://input"), true);
file_put_contents('update_log.json', json_encode($update, JSON_PRETTY_PRINT));
$chatId = $update['message']['chat']['id'] ?? null;
$text = $update['message']['text'] ?? '';
$username = $update['message']['from']['username'] ?? 'неизвестно';
$firstName = $update['message']['from']['first_name'] ?? '';
$fullName = trim($firstName);

function sendMessage($chatId, $message, $token) {
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $postData = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML',
    ];
    file_get_contents($url . '?' . http_build_query($postData));
}

function sendVideo($chatId, $videoFileId, $caption, $token) {
    $url = "https://api.telegram.org/bot$token/sendVideo";

    $postFields = [
        'chat_id' => $chatId,
        'video' => $videoFileId,
        'caption' => $caption
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    $output = curl_exec($ch);

    // логируем ответ Telegram (для отладки)
    file_put_contents('curl_log.txt', $output);

    curl_close($ch);
}
if ($text !== '/start' &&  !in_array($text, ['📅 Записаться', '❓ Вопрос/ответ', '📍 Как добраться', '💍 Свадебные сборы', '📸 Примеры работ', '💰 Прайс', '🎓 Обучение', '🔸 Брови']))
{
    $adminMessage = "📩 Новое сообщение от @$username ($fullName):\n\n$text";
    sendMessage($adminId, $adminMessage, $token);

    sendMessage($chatId, "Спасибо! Ваше сообщение передано мастеру 💌", $token);
}

elseif ($text === '/start') {
    $keyboard = [
        'keyboard' => [
            [['text' => '📅 Записаться'], ['text' => '❓ Вопрос/ответ']],
            [['text' => '📍 Как добраться'], ['text' => '💍 Свадебные сборы']],
            [['text' => '📸 Примеры работ'], ['text' => '💰 Прайс']],
            [['text' => '🎓 Обучение'], ['text' => '🔸 Брови']],
        ],
        'resize_keyboard' => true,
        'one_time_keyboard' => false,
    ];

    $replyMarkup = json_encode($keyboard);
    $message = "Привет! Я помогу тебе узнать всё о мастере 🌸";

    file_get_contents($apiURL . "sendMessage?" . http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
            'reply_markup' => $replyMarkup,
        ]));
} elseif ($text === '📅 Записаться') {
    $message = "Чтобы записаться, перейдите по ссылке: https://dikidi.net/484873"; // тут вставь нужную ссылку
    file_get_contents($apiURL . "sendMessage?" . http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
        ]));
} elseif ($text === '❓ Вопрос/ответ') {
    $message = "Часто задаваемые вопросы:\n1. Вопрос 1\n2. Вопрос 2\n3. Вопрос 3";
    file_get_contents($apiURL . "sendMessage?" . http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
        ]));
} elseif ($text === '📍 Как добраться') {
    sendVideo(
        $chatId,
        'BAACAgIAAxkBAAMSaF2EUIFux4YaOHMYYdfUXi577ioAApJ5AAKEjvFKYPkHHLhdyhA2BA', // твой file_id
        "Как добраться до салона:\nул. Примерная, д. 10",
        $token
    );
} elseif ($text === '💍 Свадебные сборы') {
    $message = "Тут текст про свадебные сборы";
    file_get_contents($apiURL . "sendMessage?" . http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
        ]));
} elseif ($text === '📸 Примеры работ') {
    $message = "Тут будут примеры работ. Картинки";
    file_get_contents($apiURL . "sendMessage?" . http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
        ]));
} elseif ($text === '💰 Прайс') {
    $message = "Тут цены";
    file_get_contents($apiURL . "sendMessage?" . http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
        ]));
} elseif ($text === '🎓 Обучение') {
    $message = "Тут про обучение добавить";
    file_get_contents($apiURL . "sendMessage?" . http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
        ]));
} elseif ($text === '🔸 Брови') {
    $message = "Тут про брови че-то";
    file_get_contents(Config::class->getConfig($apiURL) . "sendMessage?" . http_build_query([
            'chat_id' => $chatId,
            'text' => $message,
        ]));
}
