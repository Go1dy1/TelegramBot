<?php

require_once 'UserStorageInterface.php';
require_once 'config.php';

class DbUserStorage implements UserStorageInterface
{
   public $pdo;

    public function connectDatabase($db_config)
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
    public function storeUser($id, $username){

        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE telegram_id = ?');
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();


        if ($count == 0) {
            $stmt = $this->pdo->prepare('INSERT INTO users (telegram_id, name) VALUES (?, ?)');
            $stmt->execute([$id, $username]);
        }
    }
    public function getUsers()
    {
        $users = [];
        $stmt = $this->pdo->query('SELECT telegram_id, name FROM users');
        $db_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($db_users as $row)
        {
            $users[$row['telegram_id']] = $row['name'];
        }

        return $users;
    }
    public function getUsersName()
    {
        $stmt = $this->pdo->query('SELECT name FROM users');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }



}
