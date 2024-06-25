<?php

require_once 'UserStorageInterface.php';

class FileUserStorage implements UserStorageInterface
{

    private $file;
    private $users = [];

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function storeUser($id, $username)
    {
        $this->users[$id] = $username;
        file_put_contents($this->file, json_encode($this->users));
    }

    public function getUsers()
    {
        if (file_exists($this->file))
        {
            $this->users = json_decode(file_get_contents($this->file), true);
        }
        return $this->users;
    }
    public function getUsersName()
    {
        if (file_exists($this->file))
        {
            $this->users = json_decode(file_get_contents($this->file), true);
        }
        return $this->users;
    }


}
