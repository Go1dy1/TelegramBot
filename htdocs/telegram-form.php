<?php
require_once 'TelegramBot.php';
require_once 'FileUserStorage.php';
require_once 'telegramUserManager.php';
require_once 'config.php';
require_once 'DbUserStorage.php';

$dbUserStorage = new DbUserStorage();

//$dbUserStorage->connectDatabase($db_config);
$fileUserStorage = new FileUserStorage('users.json');

$bot = new TelegramBot($token);

$userManager = new telegramUserManager($bot, $fileUserStorage);

$chats = $userManager->getUsers();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Telegram Bot Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src='Ajax.js'></script>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-4">
            <h1 class="mt-5 mb-3">Telegram Bot Form</h1>
            <br>
            <div id='response'></div>
            <form id="ajax-form" name="ourForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="chat_id" class="form-label">Chat</label>
                    <select id="chat_id" name="chat_id" class="form-control">
                        <option value="">Select Chat</option>
                        <?php foreach ($chats as $telegram_id => $name) : ?>
                            <option value="<?= htmlspecialchars($telegram_id); ?>"><?= htmlspecialchars($name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">File</label>
                    <input id="file" type="file" name="file" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" name="message" class="form-control"></textarea>
                </div>
                <button type="submit" name="submit-button" class="form-control">Submit</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

