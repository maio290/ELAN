<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class AusbildungsverhaeltnisController extends BaseController
{

    static function checkPermission($AVID, $username)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $AVID = $db->escapeString($AVID);
        $result = $db->executeQuery("SELECT azubi FROM Ausbildungsverhaeltnis WHERE id =" . $AVID);
        if ($result) {
            $row = $result->fetch_assoc();
            if (strcasecmp($row['azubi'], $username) === 0) {
                return true;;
            }
        }
        return false;
    }

    static function checkAusbilderPermission($AVID, $username)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $AVID = $db->escapeString($AVID);
        $result = $db->executeQuery("SELECT ausbilder FROM Ausbildungsverhaeltnis WHERE id =" . $AVID);
        if ($result) {
            $row = $result->fetch_assoc();
            if (strcasecmp($row['ausbilder'], $username) === 0) {
                return true;;
            }
        }
        return false;
    }

    static function getAusbildungsverhaeltnisByID($AVID)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $AVID = $db->escapeString($AVID);
        $res = $db->executeQuery("SELECT * FROM `Ausbildungsverhaeltnis` WHERE `id` = '" . $AVID . "' LIMIT 1");
        $row = $res->fetch_assoc();

        if ($row) {
            return ClassMapper::mapMapToObject($row, Ausbildungsverhaeltnis::class);
        }

        return $row;
    }

    static function getCurrentAusbildungsverhaeltnisse()
    {
        $ausbildungsverhaeltnisse = [];
        $db = self::getConfig()->createDatabaseConnection();
        $result = $db->executeQuery("SELECT * FROM Ausbildungsverhaeltnis WHERE end > curdate()") or die("Failed to acquire data from database");
        while ($row = $result->fetch_assoc()) {
            $ausbildungsverhaeltnis = ClassMapper::mapMapToObject($row, Ausbildungsverhaeltnis::class);
            $ausbildungsverhaeltnisse[] = $ausbildungsverhaeltnis;
        }
        return $ausbildungsverhaeltnisse;

    }

    static function getAusbildungsverhaeltnisForUser($username)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $username = $db->escapeString($username);
        $res = $db->executeQuery("SELECT * FROM `Ausbildungsverhaeltnis` WHERE `azubi` = '" . $username . "' LIMIT 1");
        $row = $res->fetch_assoc();

        if ($row) {
            return ClassMapper::mapMapToObject($row, Ausbildungsverhaeltnis::class);
        }

        return $row;
    }

    static function getAusbildungsverhaeltnisForAusbilder($username)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $username = $db->escapeString($username);
        $res = $db->executeQuery("SELECT * FROM `Ausbildungsverhaeltnis` WHERE `ausbilder` = '" . $username . "'");
        $ausbildungsverhaeltnisse = [];

        while ($row = $res->fetch_assoc()) {
            $ausbildungsverhaeltnisse[] = ClassMapper::mapMapToObject($row, Ausbildungsverhaeltnis::class);
        }

        return $ausbildungsverhaeltnisse;
    }

    static function getAusbildungsverhaeltnisForKey($key)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $key = $db->escapeString($key);
        $res = $db->executeQuery("SELECT * FROM Ausbildungsverhaeltnis WHERE `key` ='$key'");
        if ($res) {
            $row = $res->fetch_assoc();
            return ClassMapper::mapMapToObject($row, Ausbildungsverhaeltnis::class);
        }
        return $res;
    }


}