<?php
$botToken = "7676225387:AAF_96xtXnB6oFR6Ja2J8LfyLN1TWBi6ajM";
$apiUrl = "https://api.telegram.org/bot$botToken/";

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update || !isset($update["message"])) {
    exit;
}

$chatId = $update["message"]["chat"]["id"];
$text = $update["message"]["text"] ?? '';

if ($text === "/start") {
    $keyboard = [
        "keyboard" => [
            [["text" => "Play"]]
        ],
        "one_time_keyboard" => true,
        "resize_keyboard" => true
    ];

    $data = [
        'chat_id' => $chatId,
        'text' => "Welcome! Choose an option:",
        'reply_markup' => json_encode($keyboard)
    ];

    file_get_contents($apiUrl . "sendMessage?" . http_build_query($data));
} elseif ($text === "Play") {
    $data = [
        'chat_id' => $chatId,
        'text' => "Let's play! Open this: https://t.me/rewardbazar_bot/app"
    ];

    file_get_contents($apiUrl . "sendMessage?" . http_build_query($data));
} else {
    $data = [
        'chat_id' => $chatId,
        'text' => "I didn't understand that. Try pressing Play!"
    ];

    file_get_contents($apiUrl . "sendMessage?" . http_build_query($data));
}
?>