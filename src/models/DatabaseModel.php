<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class DatabaseModel
{
    static private $dbCon;

    static function getDatabaseConnection()
    {
        if (!isset(self::$dbCon)) {
            $config = new Config_Provider();
            self::$dbCon = $config->createDatabaseConnection();
        }
        return self::$dbCon;
    }


}