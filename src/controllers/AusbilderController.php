<?php


namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class AusbilderController extends BaseController
{

    static function getAusbilderStateForUser($username)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $username = $db->escapeString($username);
        $res = $db->executeQuery("SELECT * FROM Ausbildungsverhaeltnis WHERE lower(ausbilder) = '" . strtolower($username) . "'");
        if ($res) {
            return $res->num_rows;
        }
        return $res;

    }

}