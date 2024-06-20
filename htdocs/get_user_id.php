<?php

class TelegramUserManager {
    private $pdo;
    private $api_url;

    public function __construct($token, $db_config) {
        $this->api_url = 'https://api.telegram.org/bot' . $token . '/getUpdates';
        $this->connectDatabase($db_config);
    }

    private function connectDatabase($db_config) {
        $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], $options);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public function fetchAndStoreUsers() {
        $response = file_get_contents($this->api_url);
        $response = json_decode($response, true);

        $unique_users = [];

        if (isset($response['result'])) {
            foreach ($response['result'] as $update) {
                if (isset($update['message']['from']['id'])) {
                    $user_id = $update['message']['from']['id'];
                    $user_name = $update['message']['from']['username'] ?? 'No username';

                    if (!isset($unique_users[$user_id])) {
                        $unique_users[$user_id] = $user_name;
                    }
                }
            }

            foreach ($unique_users as $id => $username) {
                $this->storeUser($id, $username);
            }
        } else {
            echo "No updates found.";
        }
    }

    private function storeUser($id, $username) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE telegram_id = ?');
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $stmt = $this->pdo->prepare('INSERT INTO users (telegram_id, name) VALUES (?, ?)');
            $stmt->execute([$id, $username]);
            echo "Inserted User ID: $id, Username: $username\n";
        } else {
            echo "User ID: $id already exists\n";
        }
    }
}

?>
