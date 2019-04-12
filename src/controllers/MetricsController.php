<?php


namespace ELAN;
class MetricsController extends BaseController
{

    static function getMetricsForUser($SESSION)
    {
        $db = self::getConfig()->createDatabaseConnection();
        $user = User::deserialize($SESSION['user']);

        $azubis = AusbilderController::getAusbilderStateForUser($user->username);
        if ($azubis !== 0) {
            $ausstehend = self::getCountByStateForAusbilder($db, $user->username, Freigabestatus::NEU);
            $freigegeben = self::getCountByStateForAusbilder($db, $user->username, Freigabestatus::FREIGEGEBEN);
            $korrektur = self::getCountByStateForAusbilder($db, $user->username, Freigabestatus::FEHLERHAFT);
            $signiert = self::getCountByStateForAusbilder($db, $user->username, Freigabestatus::SIGNIERT);
            $metrics = new AusbilderMetrics($azubis, $ausstehend, $freigegeben, $korrektur, $signiert);
            return MetricsView::generateAusbilderView($metrics);
        } else {
            $end = self::getEndOfAusbildung($db, $user);
            $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForUser($user->username);
            $geschrieben = self::getCountByState($db, $ausbildungsverhaeltnis, Freigabestatus::BEARBEITET);
            $freigegeben = self::getCountByState($db, $ausbildungsverhaeltnis, Freigabestatus::FREIGEGEBEN);
            $korrektur = self::getCountByState($db, $ausbildungsverhaeltnis, Freigabestatus::FEHLERHAFT);
            $toWrite = self::getCountByState($db, $ausbildungsverhaeltnis, Freigabestatus::NEU);
            $written = self::getCountByState($db, $ausbildungsverhaeltnis, Freigabestatus::SIGNIERT);
            $metrics = new AzubiMetrics($geschrieben, $freigegeben, $korrektur, $toWrite, $written, $end);
            return MetricsView::generate($metrics);
        }
    }

    private static function getCountByStateForAusbilder($db, $ausbilder, $state)
    {
        $ausbilder = $db->escapeString($ausbilder);
        $res = $db->executeQuery("SELECT COUNT(*) FROM `Ausbildungsnachweis` WHERE `AVID` IN (SELECT id FROM Ausbildungsverhaeltnis WHERE ausbilder = '$ausbilder') AND `Freigabestatus` = " . $state);
        if ($res) {
            $row = $res->fetch_assoc();
            return $row['COUNT(*)'];
        }
        return 0;
    }

    private static function getEndOfAusbildung($db, $user)
    {
        $username = $db->escapeString($user->username);
        $res = $db->executeQuery("SELECT `end` FROM `Ausbildungsverhaeltnis` WHERE `azubi` = '" . $username . "';");
        if ($res) {
            $row = $res->fetch_assoc();
            return $row['end'];
        }
        return $res;
    }

    private static function getCountByState($db, $ausbildungsverhaeltnis, $state)
    {
        if($ausbildungsverhaeltnis)
        {
            $avid = $db->escapeString($ausbildungsverhaeltnis->id);
            $res = $db->executeQuery("SELECT COUNT(*) FROM `Ausbildungsnachweis` WHERE `AVID` = " . $avid . " AND `Freigabestatus` = " . $state);
            if ($res) {
                $row = $res->fetch_assoc();
                return $row['COUNT(*)'];
            }
            return 0;
        }
        return 0;
    }
}