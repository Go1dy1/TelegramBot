<?php
require_once './TelegramBot.php';

$token = '7229935037:AAFqPbL2bXNTYixRtM9CnxpD7Ex7zh5nt5Q';

$db_config = [
    'host' => 'mysql',
    'dbname' => 'Telegram_bot',
    'user' => 'Telegram_bot',
    'pass' => 'Telegram_bot'
];
$bot = new TelegramBot($token, $db_config);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $users = $bot->getUsers();
    $message = $_POST['message'];

    if (!empty($message) && empty($_FILES['file']['tmp_name']))
    {
        $bot->sendMessage(['text' => $message]);
        echo 'Message sent successfully!';
    }
    elseif (!empty($_FILES['file']['tmp_name']))
    {
        $filePath = $_FILES['file']['tmp_name'];
        $filename = $_FILES['file']['name'];
        $caption = !empty($message) ? $message : '';

        $fileType = mime_content_type($filePath);
        $params = ['media' => $filePath, 'caption' => $caption];

        switch ($fileType) {
            case 'image/jpeg':
            case 'image/png':
                $bot->sendPhoto($params);
                break;
            case 'audio/mpeg':
                $bot->sendAudio($params);
                break;
            case 'video/mp4':
            case 'video/mpeg':
            case 'video/webm':
                $bot->sendVideo($params);
                break;
            case 'image/gif':
                $bot->sendAnimation($params);
                break;
            case 'audio/ogg':
                $bot->sendVoice($params);
                break;
            default:
                echo 'Unsupported file type';
        }
        echo 'File sent successfully!';
    }
    else {
        echo 'Empty message!';
    }
}
