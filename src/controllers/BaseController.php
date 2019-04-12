<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class BaseController
{
    private static $config;
    private static $LDAP;

    function getLDAP()
    {
        if (!isset(self::$LDAP)) {
            if (!isset(self::$config)) {
                self::getConfig();
            }
            self::$LDAP = new LDAP_Provider(self::$config);
        }
        return self::$LDAP;

    }

    function getConfig()
    {
        if (!isset(self::$config)) {
            self::$config = new Config_Provider();
        }
        return self::$config;
    }

}