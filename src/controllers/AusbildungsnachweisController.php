<?php


namespace ELAN;

use ELAN\controllers\EasterEggController;

require_once(__DIR__ . '/../../vendor/autoload.php');

class AusbildungsnachweisController extends BaseController
{

    static function generateEmptyAusbildungsnachweiseForNewAusbildungsverhaeltnis($ausbildungsverhaeltnis)
    {
        // it's ensured that start is always a monday and end is always a sunday
        $start = strtotime($ausbildungsverhaeltnis->start);
        $end = strtotime($ausbildungsverhaeltnis->end);
        $id = 1;
        while ($start < $end) {

            $startDate = date("Y-m-d", $start);
            $ausbildungsnachweis = new Ausbildungsnachweis($id++, $ausbildungsverhaeltnis->id, $startDate);
            $res = $ausbildungsnachweis->persist();
            $start += 604800;
        }

    }

    static function getNextAusbildungsnachweisByStateForAzubi($user, $state)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $state = $db->escapeString($state);
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForUser($user->username);
        if ($ausbildungsverhaeltnis !== false) {
            $res = $db->executeQuery("SELECT * FROM `Ausbildungsnachweis` WHERE `Freigabestatus` = $state AND `AVID` = " . $ausbildungsverhaeltnis->id . "  ORDER BY  `Start` ASC LIMIT 1");
            if ($res && !$res->num_rows <= 0) {
                $row = $res->fetch_assoc();
                $ausbildungsnachweis = ClassMapper::mapMapToObject($row, Ausbildungsnachweis::class);
                return NavbarView::generate($_SESSION) . AusbildungsnachweisView::generateAzubiQueueView($ausbildungsnachweis, $ausbildungsverhaeltnis);
            }
            $view = '';
            $view .= NavbarView::generate($_SESSION);
            $view .= "<h2>Keine Einträge vorhanden, dafür gibt es ein bisschen Musik!</h2>";
            $view .= EasterEggController::getSomeMusic();
            return $view;
        }
    }

    static function getAusbildungsnachweiseByAVID($AVID)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $AVID = $db->escapeString($AVID);
        $res = $db->executeQuery("SELECT * FROM `Ausbildungsnachweis` WHERE AVID = $AVID");
        $ausbildungsnachweise = [];
        while ($row = $res->fetch_assoc()) {
            $ausbildungsnachweise[] = ClassMapper::mapMapToObject($row, Ausbildungsnachweis::class);
        }
        return $ausbildungsnachweise;
    }

    static function getCurrentAusbildungsnachweisForKey($key)
    {
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForKey($key);
        $db = self::getConfig()->createDatabaseConnection();
        $now = strtotime(date("Y-m-d"));
        $start = date("Y-m-d", TimeUtils::setDateToSpecificWeekday($now, Weekdays::MONDAY));

        // try to select current week
        $res = $db->executeQuery("SELECT * FROM Ausbildungsnachweis WHERE AVID = '$ausbildungsverhaeltnis->id' AND Start = '$start' LIMIT 1");
        if ($res) {
            return ClassMapper::mapMapToObject($res->fetch_assoc(), Ausbildungsnachweis::class);
        } else {
            $res = $db->executeQuery("SELECT * FROM Ausbildungsnachweis WHERE AVID = '$ausbildungsverhaeltnis->id' ORDER BY Start DESC LIMIT 1");
            if ($res) {
                return ClassMapper::mapMapToObject($res->fetch_assoc(), Ausbildungsnachweis::class);
            }
            return false;
        }

    }

    static function getCurrentAusbildungsnachweisForKeyAndID($key,$ID)
    {
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForKey($key);
        $db = self::getConfig()->createDatabaseConnection();
        $ID = $db->escapeString($ID);
        // try to select current week
        $res = $db->executeQuery("SELECT * FROM Ausbildungsnachweis WHERE AVID = '$ausbildungsverhaeltnis->id' AND ID = '$ID'  LIMIT 1");
        if ($res) {
            $row = $res->fetch_assoc();
            return ClassMapper::mapMapToObject($row, Ausbildungsnachweis::class);
        }
        return false;
    }

    static function selectNextEntryToSignForAusbilder($ausbilder)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $result = $db->executeQuery("SELECT * FROM `Ausbildungsnachweis` WHERE `Freigabestatus` = " . Freigabestatus::FREIGEGEBEN . " AND `AVID` IN (SELECT `id` FROM `Ausbildungsverhaeltnis` WHERE lower(`ausbilder`) = '" . strtolower($ausbilder->username) . "') ");
        if ($result && !$result->num_rows <= 0) {
            $row = $result->fetch_assoc();
            return NavbarView::generate($_SESSION) . self::getAusbilderViewForIDAndAVID($row['ID'], $row['AVID'], true);
        } else {

        }
        echo NavbarView::generate($_SESSION);
        echo "<h2>Keine Einträge vorhanden, dafür gibt es ein bisschen Musik!</h2>";
        echo EasterEggController::getSomeMusic();
    }

    static function getAusbilderViewForIDAndAVID($id, $AVID, $isQueue = false)
    {
        $ausbildungsnachweis = self::getAusbildungsnachweisByIDAndAVID($id, $AVID);
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisByID($AVID);
        if ($isQueue) {
            return AusbildungsnachweisView::generateAusbilderQueueView($ausbildungsnachweis, $ausbildungsverhaeltnis);
        } else {
            return AusbildungsnachweisView::generateAusbilderView($ausbildungsnachweis, $ausbildungsverhaeltnis);
        }


    }

    static function getAusbildungsnachweisByIDAndAVID($ID, $AVID)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $ID = $db->escapeString($ID);
        $AVID = $db->escapeString($AVID);
        $res = $db->executeQuery("SELECT * FROM `Ausbildungsnachweis` WHERE ID = $ID AND AVID = $AVID");
        $row = $res->fetch_assoc();
        if ($row) {
            return ClassMapper::mapMapToObject($row, Ausbildungsnachweis::class);
        }
        return false;
    }

    static function getViewForIDAndAVID($id, $AVID)
    {
        $ausbildungsnachweis = self::getAusbildungsnachweisByIDAndAVID($id, $AVID);
        return AusbildungsnachweisView::generate($ausbildungsnachweis);
    }

    static function getViewForSession($SESSION)
    {
        return AusbildungsnachweisView::generate(self::getCurrentAusbildungsnachweis($SESSION));
    }

    static function getCurrentAusbildungsnachweis($SESSION)
    {
        $user = User::deserialize($SESSION['user']);
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForUser($user->username);
        $db = self::getConfig()->createDatabaseConnection();
        $now = strtotime(date("Y-m-d"));
        $now = date("Y-m-d", TimeUtils::setDateToSpecificWeekday($now, Weekdays::MONDAY));
        $res = $db->executeQuery("SELECT * FROM Ausbildungsnachweis WHERE Start = '" . $now . "' AND AVID = " . $db->escapeString($ausbildungsverhaeltnis->id));
        $row = $res->fetch_assoc();

        if ($row) {
            return ClassMapper::mapMapToObject($row, Ausbildungsnachweis::class);
        } else {
            //Fallback: most recent entry
            $result = $db->executeQuery("SELECT * FROM Ausbildungsnachweis WHERE AVID = " . $db->escapeString($ausbildungsverhaeltnis->id) . " ORDER BY Start DESC LIMIT 1");
            $row = $result->fetch_assoc();
            if ($row) {
                return ClassMapper::mapMapToObject($row, Ausbildungsnachweis::class);
            }
        }
        return $row;
    }

}