<?php
/**
 * @method getMe()
 * @method sendMessage($params)
 * @method sendPhoto($params)
 * @method sendAudio($params)
 * @method sendVideo($params)
 * @method sendAnimation($params)
 * @method sendVoice($params)
 *
 */

class TelegramBot
{
    private $token;
    private $api_url = 'https://api.telegram.org/bot';
    private $pdo;

    public function __construct($token, $db_config)
    {
        $this->token = $token;
        $this->connectDatabase($db_config);
    }

    private function connectDatabase($db_config)
    {
        $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], $options);
        }
        catch (PDOException $e)
        {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    private function makeRequest($endpoint, $params = [], $isMultipart = false)
    {
        $url = $this->api_url . $this->token . '/' . $endpoint;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);

        if ($isMultipart) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        else {
            $payload = json_encode($params);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    public function __call($name, $arguments)
    {
        $stmt = $this->pdo->query('SELECT telegram_id FROM users');
        $users = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($users as $user_id) {
            $params = array_merge($arguments[0], ['chat_id' => $user_id]);
            if (isset($arguments[0]['media']))
            {
                $params[$this->getMediaType($name)] = new CURLFile(realpath($arguments[0]['media']));
                $this->makeRequest($name, $params, true);
            }
            else {
                $this->makeRequest($name, $params, false);
            }
        }
    }

    private function getMediaType($methodName)
    {
        $mediaTypes = [
            'sendPhoto' => 'photo',
            'sendAudio' => 'audio',
            'sendVideo' => 'video',
            'sendAnimation' => 'animation',
            'sendVoice' => 'voice'
        ];
        return $mediaTypes[$methodName] ?? null;
    }

    public function getUsers()
    {
        $stmt = $this->pdo->query('SELECT telegram_id FROM users');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
