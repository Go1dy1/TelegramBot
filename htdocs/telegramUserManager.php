<?php
/*
 * @method $storage UserStorageInterface
 */
class telegramUserManager {
    public $storage;
    public $bot;

    public function __construct( TelegramBot $bot, UserStorageInterface $storage) {
        $this->bot = $bot;
        $this->storage = $storage;

        $this->fetchAndStoreUsers();
    }
    public function fetchAndStoreUsers() {
        // Получение обновлений от Telegram
        $response = $this->bot->getUpdates();

        $unique_users = [];

        if (isset($response->result)) {
            foreach ($response->result as $update) {
                if (isset($update->message->from->id)) {
                    $user_id = $update->message->from->id;
                    $user_name = $update->message->from->username ?? 'No username';

                    if (!isset($unique_users[$user_id])) {
                        $unique_users[$user_id] = $user_name;
                    }
                }
            }

            foreach ($unique_users as $id => $username) {
                $this->storage->storeUser($id, $username);
            }
        } else {
            echo "No updates found.";
        }
    }


    public function getUsers()
    {
        return $this->storage->getUsers();
    }
    public function getUsersName()
    {
        return $this->storage->getUsers();
    }

}

?>
