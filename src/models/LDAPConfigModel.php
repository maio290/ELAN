<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class LDAPConfigModel
{

    public $host;
    public $port;
    public $base_dn;
    public $username;
    public $password;
    public $appendix;

    function __construct($host, $port, $base_dn, $username, $password, $appendix)
    {
        $this->host = $host;
        $this->port = $port;
        $this->base_dn = $base_dn;
        $this->username = $username;
        $this->password = $password;
        $this->appendix = $appendix;
    }
}