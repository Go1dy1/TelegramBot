<?php

Interface UserStorageInterface
{
    public function storeUser($id, $username);
   // public function connectDatabase($db_config);
    public function getUsers();
    public function getUsersName();
}
