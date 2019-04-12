<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class User
{

    public $username;
    public $displayname;
    public $isAdmin;


    function __construct($username = null, $displayname = null, $isAdmin = false)
    {
        $this->username = $username;
        $this->displayname = $displayname;
        $this->isAdmin = $isAdmin;
    }

    static function deserialize($str)
    {
        $data = json_decode($str);
        return new User($data->username, $data->displayname, $data->isAdmin);
    }

    function serialize()
    {
        return json_encode($this);
    }

}