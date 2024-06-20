<?php
require_once './TelegramBot.php';
require_once './get_user_id.php';

$telegram_id = 804626749;

$token = '7229935037:AAFqPbL2bXNTYixRtM9CnxpD7Ex7zh5nt5Q';

$db_config = [
        'host' => 'mysql',
        'dbname' => 'Telegram_bot',
        'user' => 'Telegram_bot',
        'pass' => 'Telegram_bot'
];

$bot = new TelegramBot($token, $db_config);

$userManager = new TelegramUserManager($token, $db_config);
$userManager->fetchAndStoreUsers();

$chats = [
        $telegram_id => 'Goldy bot',
        2 => 'User 2',
        3 => 'User 3',
        4 => 'User 4',
        5 => 'User 5',
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Telegram Bot Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="ajax.js"></script>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-4">
            <h1 class="mt-5 mb-3">Telegram Bot Form</h1> <br>
            <div id='response'></div>

            <form id="ajax-form" name="ourForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="chat_id" class="form-label">Chat</label>
                    <select id="chat_id" name="chat_id" class="form-control">
                        <option value="">Select Chat</option>
                        <?php foreach ($chats as $chat_id => $user_name) : ?>
                            <option value="<?= $chat_id; ?>"><?= $user_name; ?></option>
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

            <script src='Ajax.js'></script>
        </div>
    </div>
</div>

</body>
</html>
