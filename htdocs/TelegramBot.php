<?php
require_once "config.php";
require_once "DbUserStorage.php";
require_once 'telegramUserManager.php';
/**
 * @method getMe()
 * @method sendMessage($chat_id,$params)
 * @method sendPhoto($chat_id,$params)
 * @method sendAudio($chat_id,$params)
 * @method sendVideo($chat_id,$params)
 * @method sendAnimation($chat_id,$params)
 * @method sendVoice($chat_id,$params)
 * @method getUpdates($params)
 *
 */

class TelegramBot {
    private $token;
    private $api_url = 'https://api.telegram.org/bot';
    public $db;
    public $telegramUserManager;

    public function __construct($token) {
        $this->token = $token;
    }

    private function makeRequest($endpoint, $params = [], $isMultipart = false) {
        $url = $this->api_url . $this->token . '/' . $endpoint;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);

        if ($isMultipart) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        } else {
            $payload = json_encode($params);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response);
    }

    public function __call($name, $arguments) {
        $params = $arguments[0];

        if (!isset($params['chat_id'])) {
            throw new InvalidArgumentException("chat_id is required in arguments for method $name");
        }

        if (isset($params['media'])) {
            $params[$this->getMediaType($name)] = new CURLFile(realpath($params['media']));
            $this->makeRequest($name, $params, true);
        } else {
            $this->makeRequest($name, $params, false);
        }
    }

    private function getMediaType($methodName) {
        $mediaTypes = [
            'sendPhoto' => 'photo',
            'sendAudio' => 'audio',
            'sendVideo' => 'video',
            'sendAnimation' => 'animation',
            'sendVoice' => 'voice'
        ];
        return $mediaTypes[$methodName] ?? null;
    }

    /*
      public function __call($name, $arguments)
    {
        $stmt = $pdo->query('SELECT telegram_id FROM users');
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
     */
}
