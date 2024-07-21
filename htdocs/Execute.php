<?php
require_once 'TelegramBot.php';
require_once 'config.php';

$bot = new TelegramBot($token);

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chat_id = $_POST['chat_id'];
    $message = $_POST['message'];

    if (empty($chat_id)) {
        $response['error'] = 'Chat ID is required!';
    } elseif (!empty($message) && empty($_FILES['file']['tmp_name'])) {
        $bot->sendMessage(['chat_id' => $chat_id, 'text' => $message]);
        $response['success'] = 'Message sent successfully!';
    } elseif (!empty($_FILES['file']['tmp_name'])) {
        $filePath = $_FILES['file']['tmp_name'];
        $filename = $_FILES['file']['name'];
        $caption = !empty($message) ? $message : '';

        $fileType = mime_content_type($filePath);
        $params = ['chat_id' => $chat_id, 'media' => $filePath, 'caption' => $caption];

        switch ($fileType) {
            case 'image/jpeg':
            case 'image/png':
                $bot->sendPhoto($params);
                break;
            case 'audio/mpeg':
                $bot->sendAudio($params);
                break;
            case 'video/mp4':
            case 'video/quicktime':
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
                $response['error'] = 'Unsupported file type!';
        }
        if (!isset($response['error'])) {
            $response['success'] = 'File sent successfully!';
        }
    } else {
        $response['error'] = 'Message or file is required!';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
